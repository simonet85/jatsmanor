<!-- Résumé de la réservation -->
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <h3 class="font-semibold mb-4">Résumé de la réservation</h3>
    <div class="flex gap-4 items-center mb-4">
        <img
            src="{{ asset('images/' . ($room->image ?? 'chambre1.png')) }}"
            alt="{{ $room->nom ?? 'Chambre' }}"
            class="w-24 h-16 object-cover rounded"
        />
        <div>
            <div class="font-bold">
                {{ $room->nom ?? 'Chambre Standard Urbaine' }}
                @if(isset($room->rating))
                    <span class="text-yellow-500">
                        <i class="fas fa-star"></i> {{ $room->rating }}
                    </span>
                @endif
            </div>
            <div class="text-xs text-gray-500">{{ $room->location ?? 'Cocody - 1er étage' }}</div>
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
                <i class="fas fa-wifi"></i> WiFi gratuit
            </span>
            <span class="bg-gray-100 text-xs px-2 py-1 rounded flex items-center gap-1">
                <i class="fas fa-snowflake"></i> Climatisation
            </span>
            <span class="bg-gray-100 text-xs px-2 py-1 rounded flex items-center gap-1">
                <i class="fas fa-tv"></i> TV HD
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
            Sélectionnez vos dates pour voir le prix total
        </div>
    @endif
    
    <!-- Avantages -->
    <ul class="text-xs text-green-700 space-y-1 mb-2">
        <li><i class="fas fa-check"></i> Annulation gratuite jusqu'à 48h avant</li>
        <li><i class="fas fa-check"></i> Confirmation immédiate</li>
        <li><i class="fas fa-check"></i> Support client 24h/24</li>
    </ul>
</div>
