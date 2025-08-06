<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of bookings for admin management
     */
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'residence']);

        // Inclure les réservations supprimées si demandé
        if ($request->filled('show_deleted') && $request->show_deleted === 'true') {
            $query->onlyTrashed();
        }

        // Recherche
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                             ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhereHas('residence', function ($residenceQuery) use ($search) {
                    $residenceQuery->where('name', 'like', "%{$search}%");
                })
                ->orWhere('id', 'like', "%{$search}%");
            });
        }

        // Filtre par statut
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filtre par période
        if ($request->filled('date_from')) {
            $query->where('check_in', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('check_out', '<=', $request->date_to);
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(15);

        // Statistiques
        $stats = [
            'total_bookings' => Booking::count(),
            'pending_bookings' => Booking::where('status', 'pending')->count(),
            'confirmed_bookings' => Booking::where('status', 'confirmed')->count(),
            'deleted_bookings' => Booking::onlyTrashed()->count(),
            'total_revenue' => Booking::where('status', 'confirmed')->sum('total_amount'),
        ];

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'html' => view('dashboard.partials.bookings-table', compact('bookings'))->render(),
                'pagination' => $bookings->links()->render(),
                'stats' => $stats
            ]);
        }

        return view('dashboard.bookings', compact('bookings', 'stats'));
    }

    /**
     * Show the form for viewing the specified booking
     */
    public function show(Booking $booking)
    {
        $booking->load(['user', 'residence', 'residence.images']);
        
        return response()->json([
            'success' => true,
            'booking' => $booking
        ]);
    }

    /**
     * Update the booking status
     */
    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed'
        ], [
            'status.required' => 'Le statut est obligatoire.',
            'status.in' => 'Le statut sélectionné n\'est pas valide.'
        ]);

        try {
            DB::beginTransaction();

            $oldStatus = $booking->status;
            $booking->update(['status' => $request->status]);

            // Log de l'activité (optionnel)
            // ActivityLog::create([...]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Statut mis à jour avec succès!',
                'booking' => $booking->fresh(['user', 'residence'])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Soft delete the specified booking
     */
    public function destroy(Booking $booking)
    {
        try {
            $booking->delete();

            return response()->json([
                'success' => true,
                'message' => 'Réservation supprimée avec succès!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Restore the specified soft deleted booking
     */
    public function restore($id)
    {
        try {
            $booking = Booking::onlyTrashed()->findOrFail($id);
            $booking->restore();

            return response()->json([
                'success' => true,
                'message' => 'Réservation restaurée avec succès!',
                'booking' => $booking->fresh(['user', 'residence'])
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la restauration: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Permanently delete the specified booking
     */
    public function forceDelete($id)
    {
        try {
            $booking = Booking::onlyTrashed()->findOrFail($id);
            $booking->forceDelete();

            return response()->json([
                'success' => true,
                'message' => 'Réservation définitivement supprimée!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression définitive: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export bookings data
     */
    public function export(Request $request)
    {
        // Logique d'export (CSV, Excel, etc.)
        // À implémenter selon les besoins
        
        return response()->json([
            'success' => true,
            'message' => 'Export en cours de développement...'
        ]);
    }
}
