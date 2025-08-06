<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Residence;
use App\Models\Booking;
use Carbon\Carbon;

class ResidenceSearchController extends Controller
{
    /**
     * Search for available residences
     */
    public function search(Request $request)
    {
        $request->validate([
            'arrival_date' => 'required|date|after_or_equal:today',
            'departure_date' => 'required|date|after:arrival_date',
            'guests' => 'required|integer|min:1|max:10',
        ]);

        $arrivalDate = Carbon::parse($request->arrival_date);
        $departureDate = Carbon::parse($request->departure_date);
        $guests = $request->guests;

        // Rechercher les résidences disponibles
        $availableResidences = $this->getAvailableResidences($arrivalDate, $departureDate, $guests);

        return view('residences.search-results', [
            'residences' => $availableResidences,
            'arrival_date' => $arrivalDate,
            'departure_date' => $departureDate,
            'guests' => $guests,
            'nights' => $arrivalDate->diffInDays($departureDate),
        ]);
    }

    /**
     * Get available residences for the given criteria
     */
    private function getAvailableResidences($arrivalDate, $departureDate, $guests)
    {
        // Obtenir les IDs des résidences déjà réservées pour cette période
        $bookedResidenceIds = Booking::where('status', '!=', 'cancelled')
            ->where(function ($query) use ($arrivalDate, $departureDate) {
                $query->whereBetween('check_in', [$arrivalDate, $departureDate])
                    ->orWhereBetween('check_out', [$arrivalDate, $departureDate])
                    ->orWhere(function ($subQuery) use ($arrivalDate, $departureDate) {
                        $subQuery->where('check_in', '<=', $arrivalDate)
                                ->where('check_out', '>=', $departureDate);
                    });
            })
            ->pluck('residence_id')
            ->toArray();

        // Retourner les résidences disponibles
        return Residence::where('is_active', true)
            ->where('capacity', '>=', $guests)
            ->whereNotIn('id', $bookedResidenceIds)
            ->with(['images', 'amenities'])
            ->orderBy('price_per_night', 'asc')
            ->get();
    }

    /**
     * Check availability for a specific residence
     */
    public function checkAvailability(Request $request, Residence $residence)
    {
        $request->validate([
            'arrival_date' => 'required|date|after_or_equal:today',
            'departure_date' => 'required|date|after:arrival_date',
            'guests' => 'required|integer|min:1|max:10',
        ]);

        $arrivalDate = Carbon::parse($request->arrival_date);
        $departureDate = Carbon::parse($request->departure_date);
        $guests = $request->guests;

        // Vérifier la capacité
        if ($residence->capacity < $guests) {
            return response()->json([
                'available' => false,
                'message' => 'Cette résidence ne peut accueillir que ' . $residence->capacity . ' invité(s).'
            ]);
        }

        // Vérifier les réservations existantes
        $conflictingBookings = Booking::where('residence_id', $residence->id)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($arrivalDate, $departureDate) {
                $query->whereBetween('check_in', [$arrivalDate, $departureDate])
                    ->orWhereBetween('check_out', [$arrivalDate, $departureDate])
                    ->orWhere(function ($subQuery) use ($arrivalDate, $departureDate) {
                        $subQuery->where('check_in', '<=', $arrivalDate)
                                ->where('check_out', '>=', $departureDate);
                    });
            })
            ->exists();

        if ($conflictingBookings) {
            return response()->json([
                'available' => false,
                'message' => 'Cette résidence n\'est pas disponible pour les dates sélectionnées.'
            ]);
        }

        // Calculer le prix total
        $nights = $arrivalDate->diffInDays($departureDate);
        $totalPrice = ($residence->price_per_night * $nights) + 
                     setting('cleaning_fee', 0) + 
                     setting('security_deposit', 0);

        return response()->json([
            'available' => true,
            'nights' => $nights,
            'price_per_night' => $residence->price_per_night,
            'cleaning_fee' => setting('cleaning_fee', 0),
            'security_deposit' => setting('security_deposit', 0),
            'total_price' => $totalPrice,
            'message' => 'Résidence disponible pour ' . $nights . ' nuit(s).'
        ]);
    }
}
