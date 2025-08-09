<!-- Résumé de la réservation -->
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <h3 class="font-semibold mb-4">Résumé de la réservation</h3>
    <div class="flex gap-4 items-center mb-4">
        <img
            src="{{ asset('images/' . ($room->image ?? 'chambre1.png')) }}"
            alt="{{ function_exists('getResidenceName') ? getResidenceName($room) : ($room->nom ?? trans('messages.room.default')) }}"
            class="w-24 h-16 object-cover rounded"
        />
        <div>
            <div class="font-bold">
                {{ function_exists('getResidenceName') ? getResidenceName($room) : ($room->nom ?? trans('messages.room.default')) }}
                @if(isset($room->rating))
                    <span class="text-yellow-500">
                        <i class="fas fa-star"></i> {{ $room->rating }}
                    </span>
                @endif
            </div>
            <div class="text-xs text-gray-500">{{ $room->location ?? trans('messages.room.default_location') }}</div>
            <div class="text-xs text-gray-500">
                Jusqu'à {{ $room->max_guests ?? 2 }} personne{{ ($room->max_guests ?? 2) > 1 ? 's' : '' }} • {{ $room->surface ?? '25' }}m²
            </div>
        </div>
    </div>
    
    <!-- Équipements -->
    <div class="flex gap-2 mb-2">
        @if($room->amenities ?? null)
            @foreach($room->amenities as $amenity)
                <span class="bg-gray-100 text-xs px-2 py-1 rounded flex items-center gap-1">
                    <i class="{{ $amenity['icon'] }}"></i> {{ $amenity['name'] }}
                </span>
            @endforeach
        @else
            <span class="bg-gray-100 text-xs px-2 py-1 rounded flex items-center gap-1">
                <i class="fas fa-wifi"></i> {{ trans('messages.amenities.wifi') }}
            </span>
            <span class="bg-gray-100 text-xs px-2 py-1 rounded flex items-center gap-1">
                <i class="fas fa-snowflake"></i> {{ trans('messages.amenities.ac') }}
            </span>
            <span class="bg-gray-100 text-xs px-2 py-1 rounded flex items-center gap-1">
                <i class="fas fa-tv"></i> {{ trans('messages.amenities.tv') }}
            </span>
        @endif
    </div>
    
    <!-- Prix -->
    @if(isset($totalPrice))
        <div class="text-lg font-bold text-blue-700 mb-2">
            {{ number_format($totalPrice) }} XOF
            @if(isset($nights))
                <span class="text-sm font-normal text-gray-500">pour {{ $nights }} nuit{{ $nights > 1 ? 's' : '' }}</span>
            @endif
        </div>
    @else
        <div class="text-xs text-gray-500 mb-2">
            {{ trans('messages.booking.select_dates') }}
        </div>
    @endif
    
    <!-- Avantages -->
    <ul class="text-xs text-green-700 space-y-1 mb-2">
        <li><i class="fas fa-check"></i> {{ trans('messages.booking.free_cancellation') }}</li>
        <li><i class="fas fa-check"></i> {{ trans('messages.booking.instant_confirmation') }}</li>
        <li><i class="fas fa-check"></i> {{ trans('messages.booking.support_24_7') }}</li>
    </ul>
</div>
