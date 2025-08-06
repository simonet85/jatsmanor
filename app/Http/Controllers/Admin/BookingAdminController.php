<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Residence;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BookingAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'permission:view-bookings']);
    }

    /**
     * Display all bookings with filters and payment management
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        $paymentStatus = $request->get('payment_status', 'all');
        $search = $request->get('search');
        
        $query = Booking::with(['user', 'residence'])
            ->orderBy('created_at', 'desc');
        
        // Apply filters
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        if ($paymentStatus !== 'all') {
            $query->where('payment_status', $paymentStatus);
        }
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('booking_reference', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('residence', function($rq) use ($search) {
                      $rq->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        $bookings = $query->paginate(15);
        
        // Statistics for dashboard
        $stats = [
            'total' => Booking::count(),
            'pending' => Booking::where('status', 'pending')->count(),
            'confirmed' => Booking::where('status', 'confirmed')->count(),
            'completed' => Booking::where('status', 'completed')->count(),
            'cancelled' => Booking::where('status', 'cancelled')->count(),
            'payment_pending' => Booking::where('payment_status', 'pending')->count(),
            'payment_paid' => Booking::where('payment_status', 'paid')->count(),
        ];
        
        return view('admin.bookings.index', compact('bookings', 'stats', 'status', 'paymentStatus', 'search'));
    }

    /**
     * Update payment status
     */
    public function updatePaymentStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,paid,failed,refunded'
        ]);

        $booking->update([
            'payment_status' => $request->payment_status
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payment status updated successfully'
        ]);
    }

    /**
     * Update booking status
     */
    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed'
        ]);

        $booking->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Booking status updated successfully'
        ]);
    }

    /**
     * Show booking details
     */
    public function show(Booking $booking)
    {
        $booking->load(['user', 'residence', 'residence.images']);
        
        return view('admin.bookings.show', compact('booking'));
    }

    /**
     * Bulk actions for bookings
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:update_payment_status,update_status,delete',
            'booking_ids' => 'required|array',
            'booking_ids.*' => 'exists:bookings,id',
            'payment_status' => 'nullable|in:pending,paid,failed,refunded',
            'status' => 'nullable|in:pending,confirmed,cancelled,completed'
        ]);

        $bookings = Booking::whereIn('id', $request->booking_ids);
        $count = $bookings->count();

        switch ($request->action) {
            case 'update_payment_status':
                $updateData = [];
                if ($request->payment_status) {
                    $updateData['payment_status'] = $request->payment_status;
                }
                $bookings->update($updateData);
                $message = "Payment status updated for {$count} booking(s)";
                break;

            case 'update_status':
                $bookings->update(['status' => $request->status]);
                $message = "Status updated for {$count} booking(s)";
                break;

            case 'delete':
                $bookings->delete();
                $message = "{$count} booking(s) deleted successfully";
                break;
        }

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    /**
     * Get booking statistics for AJAX refresh
     */
    public function getStats(Request $request)
    {
        $status = $request->get('status', 'all');
        $paymentStatus = $request->get('payment_status', 'all');
        $search = $request->get('search');
        
        $query = Booking::query();
        
        // Apply same filters as in index
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        if ($paymentStatus !== 'all') {
            $query->where('payment_status', $paymentStatus);
        }
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('booking_reference', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('residence', function($rq) use ($search) {
                      $rq->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        $baseQuery = clone $query;
        
        $stats = [
            'total' => $baseQuery->count(),
            'pending' => (clone $query)->where('status', 'pending')->count(),
            'confirmed' => (clone $query)->where('status', 'confirmed')->count(),
            'payment_pending' => (clone $query)->where('payment_status', 'pending')->count()
        ];
        
        return response()->json([
            'success' => true,
            'stats' => $stats
        ]);
    }
}
