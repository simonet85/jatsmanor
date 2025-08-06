<?php

namespace App\Http\Controllers;

use App\Models\Residence;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        
        if ($user->hasRole('Administrator')) {
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

            return view('dashboard.index', compact('stats', 'recentBookings'));
        } else {
            // Pour les clients, rediriger vers leurs réservations
            return redirect()->route('client.bookings.index');
        }
    }

    public function residences()
    {
        // Rediriger vers le nouveau contrôleur de gestion des résidences
        return redirect()->route('admin.residences.index');
    }

    public function chambres()
    {
        $rooms = \App\Models\Room::with('residence')->get();
        $residences = Residence::all();

        return view('dashboard.chambres', compact('rooms', 'residences'));
    }

    public function reservations()
    {
        // Rediriger vers le nouveau contrôleur de gestion des réservations
        return redirect()->route('admin.bookings.index');
    }

    public function utilisateurs()
    {
        // Rediriger vers le nouveau contrôleur de gestion des utilisateurs
        return redirect()->route('admin.users.index');
    }

    public function parametres()
    {
        // Rediriger vers le nouveau contrôleur des paramètres
        return redirect()->route('admin.settings.index');
    }

    public function monCompte()
    {
        return view('dashboard.mon-compte');
    }
}
