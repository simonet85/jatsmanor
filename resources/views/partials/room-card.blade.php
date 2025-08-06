<div class="bg-white shadow rounded overflow-hidden">
    @if(isset($room->image) && $room->image)
        <!-- Résidence avec image principale -->
        <img src="{{ asset($room->image) }}?v={{ time() }}" 
             alt="{{ $room->name ?? $room->nom }}" 
             class="w-full h-48 object-cover"
             loading="lazy"
             onerror="console.log('Erreur de chargement image:', this.src); this.style.display='none'; this.nextElementSibling.style.display='block';" />
        <div class="w-full h-48 bg-gray-200 flex items-center justify-center" style="display: none;">
            <i class="fas fa-home text-4xl text-gray-400"></i>
        </div>
    @elseif(isset($room->images) && $room->images->count() > 0)
        <!-- Résidence avec relation images -->
        <img src="{{ asset('storage/' . $room->images->first()->image_path) }}?v={{ time() }}" 
             alt="{{ $room->name ?? $room->nom }}" 
             class="w-full h-48 object-cover"
             loading="lazy"
             onerror="console.log('Erreur de chargement image:', this.src); this.style.display='none'; this.nextElementSibling.style.display='block';" />
        <div class="w-full h-48 bg-gray-200 flex items-center justify-center" style="display: none;">
            <i class="fas fa-home text-4xl text-gray-400"></i>
        </div>
    @else
        <!-- Image par défaut ou placeholder -->
        <div class="w-full h-48 bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center">
            <i class="fas fa-home text-4xl text-blue-400"></i>
        </div>
    @endif
    <div class="p-4">
        <h3 class="text-lg font-bold mb-1">
            @if(app()->getLocale() === 'en' && isset($room->name_en) && $room->name_en)
                {{ $room->name_en }}
            @else
                {{ $room->name ?? $room->nom }}
            @endif
        </h3>
        <p class="text-sm text-gray-600 mb-2">
            @if(app()->getLocale() === 'en' && isset($room->description_en) && $room->description_en)
                {{ $room->description_en }}
            @else
                {{ $room->description }}
            @endif
        </p>
        <p class="text-blue-700 font-semibold mb-2">
            {{ number_format($room->price_per_night ?? $room->prix, 0, ',', '.') }} FCFA / {{ trans('messages.booking.per_night') }}
        </p>
        
        @if($room->is_active ?? $room->disponible ?? true)
            <a href="{{ route('residence.details', $room->slug ?? $room->id) }}" class="bg-blue-700 hover:bg-blue-800 text-white px-4 py-2 rounded text-sm">
                {{ trans('messages.home.view_details') }}
            </a>
        @else
            <span class="bg-gray-400 text-white px-4 py-2 rounded text-sm cursor-not-allowed">
                {{ trans('messages.home.not_available') }}
            </span>
        @endif
    </div>
</div>