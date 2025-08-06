<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Administrator');
    }

    public function index(Request $request)
    {
        $query = User::with('roles');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Role filter
        if ($request->filled('role')) {
            $query->role($request->get('role'));
        }

        // Status filter
        if ($request->filled('status')) {
            $status = $request->get('status');
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Sort functionality
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');
        
        if (in_array($sort, ['name', 'email', 'created_at'])) {
            $query->orderBy($sort, $direction);
        }

        $users = $query->paginate(15);
        $roles = Role::all();

        // Statistics
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'inactive_users' => User::where('is_active', false)->count(),
            'administrators' => User::role('Administrator')->count(),
            'clients' => User::role('Client')->count(),
        ];

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'html' => view('dashboard.partials.users-table', compact('users'))->render(),
                'pagination' => $users->appends($request->all())->links()->render(),
                'stats' => $stats
            ]);
        }

        return view('dashboard.users', compact('users', 'roles', 'stats'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('dashboard.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'exists:roles,name'],
            'phone' => ['nullable', 'string', 'max:20'],
            'is_active' => ['boolean'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'is_active' => $request->boolean('is_active', true),
        ]);

        $user->assignRole($request->role);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Utilisateur créé avec succès.',
                'user' => $user->load('roles'),
            ]);
        }

        return redirect()->route('admin.users.index')
                        ->with('success', 'Utilisateur créé avec succès.');
    }

    public function show(User $user)
    {
        $user->load('roles', 'permissions', 'bookings');
        
        return response()->json([
            'success' => true,
            'user' => $user,
            'bookings_count' => $user->bookings()->count(),
            'recent_bookings' => $user->bookings()->latest()->take(5)->with('residence')->get(),
        ]);
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('dashboard.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'exists:roles,name'],
            'phone' => ['nullable', 'string', 'max:20'],
            'is_active' => ['boolean'],
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'is_active' => $request->boolean('is_active', true),
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        // Update role
        $user->syncRoles([$request->role]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Utilisateur mis à jour avec succès.',
                'user' => $user->fresh()->load('roles'),
            ]);
        }

        return redirect()->route('admin.users.index')
                        ->with('success', 'Utilisateur mis à jour avec succès.');
    }

    public function destroy(User $user)
    {
        // Prevent self-deletion
        if ($user->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Vous ne pouvez pas supprimer votre propre compte.',
            ], 403);
        }

        // Check if user has bookings
        if ($user->bookings()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Impossible de supprimer cet utilisateur car il a des réservations associées.',
            ], 409);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Utilisateur supprimé avec succès.',
        ]);
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => ['required', 'exists:roles,name'],
        ]);

        // Prevent changing own role
        if ($user->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Vous ne pouvez pas modifier votre propre rôle.',
            ], 403);
        }

        $user->syncRoles([$request->role]);

        return response()->json([
            'success' => true,
            'message' => 'Rôle mis à jour avec succès.',
            'user' => $user->fresh()->load('roles'),
        ]);
    }

    public function toggleStatus(User $user)
    {
        // Prevent self-deactivation
        if ($user->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Vous ne pouvez pas désactiver votre propre compte.',
            ], 403);
        }

        $user->update([
            'is_active' => !$user->is_active
        ]);

        $status = $user->is_active ? 'activé' : 'désactivé';

        return response()->json([
            'success' => true,
            'message' => "Utilisateur {$status} avec succès.",
            'user' => $user->fresh(),
        ]);
    }
}
