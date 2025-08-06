<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientBookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Client');
    }

    /**
     * Display client's bookings
     */
    public function index(Request $request)
    {
        $query = Booking::with(['residence'])
                       ->where('user_id', Auth::id());

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('residence', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(10);

        // Statistics for the client
        $stats = [
            'total_bookings' => Booking::where('user_id', Auth::id())->count(),
            'pending_bookings' => Booking::where('user_id', Auth::id())->where('status', 'pending')->count(),
            'confirmed_bookings' => Booking::where('user_id', Auth::id())->where('status', 'confirmed')->count(),
            'completed_bookings' => Booking::where('user_id', Auth::id())->where('status', 'completed')->count(),
            'cancelled_bookings' => Booking::where('user_id', Auth::id())->where('status', 'cancelled')->count(),
        ];

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'html' => view('client.partials.bookings-table', compact('bookings'))->render(),
                'pagination' => $bookings->appends($request->all())->links()->render(),
                'stats' => $stats
            ]);
        }

        return view('client.bookings', compact('bookings', 'stats'));
    }

    /**
     * Display a specific booking
     */
    public function show(Booking $booking)
    {
        // Ensure the booking belongs to the authenticated user
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Vous n\'êtes pas autorisé à voir cette réservation.');
        }

        $booking->load('residence', 'user');

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'booking' => $booking
            ]);
        }

        return view('client.booking-details', compact('booking'));
    }

    /**
     * Cancel a booking
     */
    public function cancel(Booking $booking)
    {
        // Ensure the booking belongs to the authenticated user
        if ($booking->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Vous n\'êtes pas autorisé à annuler cette réservation.'
            ], 403);
        }

        // Check if booking can be cancelled
        if ($booking->status === 'cancelled') {
            return response()->json([
                'success' => false,
                'message' => 'Cette réservation est déjà annulée.'
            ], 422);
        }

        if ($booking->status === 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'Impossible d\'annuler une réservation terminée.'
            ], 422);
        }

        // Check if it's too late to cancel (e.g., less than 24 hours before check-in)
        if ($booking->check_in->isPast()) {
            return response()->json([
                'success' => false,
                'message' => 'Impossible d\'annuler une réservation dont la date d\'arrivée est passée.'
            ], 422);
        }

        try {
            $booking->update([
                'status' => 'cancelled',
                'payment_status' => 'refunded' // Assuming automatic refund
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Réservation annulée avec succès.',
                'booking' => $booking->fresh()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'annulation de la réservation.'
            ], 500);
        }
    }
}
