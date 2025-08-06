<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Booking;
use App\Models\Residence;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Corriger les montants des réservations existantes
        $bookings = Booking::with('residence')
            ->where(function($query) {
                $query->where('total_amount', 0)
                      ->orWhere('price_per_night', 0)
                      ->orWhereNull('total_amount')
                      ->orWhereNull('price_per_night');
            })
            ->get();

        foreach ($bookings as $booking) {
            if ($booking->residence) {
                $pricePerNight = $booking->residence->price_per_night;
                $totalNights = $booking->total_nights ?: 1;
                $totalAmount = $pricePerNight * $totalNights;

                $booking->update([
                    'price_per_night' => $pricePerNight,
                    'total_amount' => $totalAmount
                ]);

                echo "✓ Réservation #{$booking->id}: {$pricePerNight} FCFA × {$totalNights} nuits = {$totalAmount} FCFA\n";
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Ne pas annuler cette correction
    }
};
