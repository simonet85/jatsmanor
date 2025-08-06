<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Residence;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:Administrator']);
    }

    /**
     * Tableau de bord principal de l'administration
     */
    public function index()
    {
        $user = Auth::user();
        
        // Statistiques pour les administrateurs
        $stats = [
            'total_residences' => Residence::count(),
            'total_bookings' => Booking::count(),
            'active_bookings' => Booking::where('status', 'confirmed')->count(),
            'total_users' => User::count(),
        ];

        $recentBookings = Booking::with(['user', 'residence'])
                                ->latest()
                                ->limit(5)
                                ->get();

        // Données pour les notifications (gérées par NotificationServiceProvider)
        $notifications = [
            'contact_messages' => [
                'pending' => 0,
                'today' => 0,
                'this_week' => 0,
                'urgent' => 0,
            ],
            'last_updated' => now()->format('H:i')
        ];

        return view('admin.dashboard.index', compact('stats', 'recentBookings', 'notifications'));
    }

    /**
     * Aperçu rapide des métriques
     */
    public function quickStats()
    {
        return response()->json([
            'total_residences' => Residence::count(),
            'total_bookings' => Booking::count(),
            'pending_bookings' => Booking::where('status', 'pending')->count(),
            'revenue_month' => Booking::where('status', 'confirmed')
                                   ->whereMonth('created_at', now()->month)
                                   ->sum('total_amount'),
            'new_users_month' => User::whereMonth('created_at', now()->month)->count()
        ]);
    }
}
