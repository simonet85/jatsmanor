<!-- Main residence details content -->
<main class="max-w-7xl mx-auto px-4 py-8 grid md:grid-cols-2 gap-10">
  <!-- Images & infos principales -->
  <div>
    <div class="mb-6">
      @if(isset($residence) && is_object($residence))
        @if($residence->image)
          <!-- Image principale de la résidence -->
          <img
            src="{{ asset($residence->image) }}?v={{ time() }}"
            alt="{{ $residence->name }}"
            class="w-full h-64 object-cover rounded-lg shadow"
            loading="lazy"
            onerror="console.log('Erreur image:', this.src); this.style.display='none'; this.nextElementSibling.style.display='flex';"
          />
          <div class="w-full h-64 bg-gradient-to-br from-blue-100 to-blue-200 rounded-lg shadow flex items-center justify-center" style="display: none;">
            <i class="fas fa-home text-blue-400 text-4xl"></i>
          </div>
        @elseif($residence->images->count() > 0)
          <!-- Images via relation -->
          <img
            src="{{ asset('storage/' . $residence->images->first()->image_path) }}?v={{ time() }}"
            alt="{{ $residence->name }}"
            class="w-full h-64 object-cover rounded-lg shadow"
            loading="lazy"
            onerror="console.log('Erreur image:', this.src); this.style.display='none'; this.nextElementSibling.style.display='flex';"
          />
          <div class="w-full h-64 bg-gradient-to-br from-blue-100 to-blue-200 rounded-lg shadow flex items-center justify-center" style="display: none;">
            <i class="fas fa-home text-blue-400 text-4xl"></i>
          </div>
        @else
          <div class="w-full h-64 bg-gradient-to-br from-blue-100 to-blue-200 rounded-lg shadow flex items-center justify-center">
            <i class="fas fa-home text-blue-400 text-4xl"></i>
          </div>
        @endif
      @else
        <img
          src="{{ $residence['image'] ?? './img/chambre1.png' }}"
          alt="{{ $residence['name'] ?? 'Chambre Single' }}"
          class="w-full h-64 object-cover rounded-lg shadow"
        />
      @endif
    </div>
    
    <h2 class="text-2xl font-bold mb-2">
      @if(isset($residence) && is_object($residence))
        @if(app()->getLocale() === 'en' && !empty($residence->name_en))
          {{ $residence->name_en }}
        @else
          {{ $residence->name }}
        @endif
      @else
        {{ $residence['name'] ?? 'Chambre Single' }}
      @endif
    </h2>
    
    <div class="flex items-center gap-2 mb-2">
      <span class="bg-yellow-500 text-white px-2 py-1 rounded text-xs flex items-center gap-1">
        <i class="fas fa-star"></i> 
        @if(isset($residence) && is_object($residence))
          {{ number_format($residence->rating, 1) }}
        @else
          {{ $residence['rating'] ?? '4.3' }}
        @endif
      </span>
      <span class="text-xs text-gray-500">
        @if(isset($residence) && is_object($residence))
          {{ $residence->location }}
        @else
          {{ $residence['location'] ?? 'Cocody, 1er étage' }}
        @endif
      </span>
    </div>
    
    <p class="text-gray-700 mb-4">
      @if(isset($residence) && is_object($residence))
        @if(app()->getLocale() === 'en')
          @if(!empty($residence->description_en))
            {{ $residence->description_en }}
          @else
            {{ $residence->description }}
          @endif
        @else
          {{ $residence->description }}
        @endif
      @else
        {{ $residence['description'] ?? 'Lit 1 place, salle de bain privée, idéale pour voyageurs solo ou professionnels. Profitez d\'un confort moderne et d\'une sécurité optimale.' }}
      @endif
    </p>
    
    <ul class="flex flex-wrap gap-3 text-sm mb-4">
      @if(isset($residence) && is_object($residence) && $residence->amenities->count() > 0)
        @foreach($residence->amenities as $amenity)
          <li class="flex items-center gap-1">
            <i class="{{ $amenity->icon ?? 'fas fa-check' }} text-blue-700"></i> {{ $amenity->name }}
          </li>
        @endforeach
      @elseif(isset($residence['amenities']))
        @foreach($residence['amenities'] as $amenity)
          <li class="flex items-center gap-1">
            <i class="fas fa-{{ $amenity['icon'] }} text-blue-700"></i> {{ $amenity['name'] }}
          </li>
        @endforeach
      @else
        <li class="flex items-center gap-1">
          <i class="fas fa-wifi text-blue-700"></i> Wi-Fi
        </li>
        <li class="flex items-center gap-1">
          <i class="fas fa-snowflake text-blue-700"></i> Climatisation
        </li>
        <li class="flex items-center gap-1">
          <i class="fas fa-tv text-blue-700"></i> Télévision
        </li>
        <li class="flex items-center gap-1">
          <i class="fas fa-broom text-blue-700"></i> Ménage inclus
        </li>
      @endif
    </ul>
    <div class="text-blue-700 font-bold text-xl mb-6">
      @if(isset($residence) && is_object($residence))
        {{ format_fcfa_per_night($residence->price_per_night) }}
      @else
        {{ $residence['price'] ?? '15.000' }} FCFA / nuit
      @endif
    </div>
    <a
      @if(isset($residence) && is_object($residence))
        href="{{ route('booking.create', $residence) }}"
      @else
        href="{{ $bookingUrl ?? 'booking.html' }}"
      @endif
      class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold px-6 py-3 rounded text-lg inline-block"
    >
      Réserver cette chambre
    </a>
  </div>
  
  <!-- Détails et services -->
  <aside>
    <div class="bg-white rounded-lg shadow p-6 mb-6">
      <h3 class="font-semibold mb-4">Détails de la chambre</h3>
      <ul class="text-sm text-gray-700 space-y-2">
        @if(isset($residence['details']))
          @foreach($residence['details'] as $detail)
            <li><i class="fas fa-{{ $detail['icon'] }} text-blue-700 mr-2"></i> {{ $detail['text'] }}</li>
          @endforeach
        @else
          <li><i class="fas fa-bed text-blue-700 mr-2"></i> 1 lit simple</li>
          <li>
            <i class="fas fa-shower text-blue-700 mr-2"></i> Salle de bain
            privée
          </li>
          <li>
            <i class="fas fa-ruler-combined text-blue-700 mr-2"></i> 18 m²
          </li>
          <li>
            <i class="fas fa-door-open text-blue-700 mr-2"></i> Accès sécurisé
          </li>
          <li>
            <i class="fas fa-calendar-day text-blue-700 mr-2"></i>
            Disponibilité immédiate
          </li>
        @endif
      </ul>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6 mb-6">
      <h3 class="font-semibold mb-4">Services inclus</h3>
      <ul class="text-sm text-gray-700 space-y-2">
        @if(isset($residence['services']))
          @foreach($residence['services'] as $service)
            <li>
              <i class="fas fa-{{ $service['icon'] }} text-blue-700 mr-2"></i> {{ $service['text'] }}
            </li>
          @endforeach
        @else
          <li>
            <i class="fas fa-bell-concierge text-blue-700 mr-2"></i> Service
            de chambre
          </li>
          <li>
            <i class="fas fa-shield-alt text-blue-700 mr-2"></i> Sécurité
            24h/24
          </li>
          <li>
            <i class="fas fa-parking text-blue-700 mr-2"></i> Parking privé
          </li>
          <li>
            <i class="fas fa-utensils text-blue-700 mr-2"></i> Petit-déjeuner
            disponible
          </li>
        @endif
      </ul>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
      <h3 class="font-semibold mb-4">Conditions</h3>
      <ul class="text-sm text-gray-700 space-y-2">
        @if(isset($residence['conditions']))
          @foreach($residence['conditions'] as $condition)
            <li>
              <i class="fas fa-check text-green-600 mr-2"></i> {{ $condition }}
            </li>
          @endforeach
        @else
          <li>
            <i class="fas fa-check text-green-600 mr-2"></i> Annulation
            gratuite jusqu'à 48h avant
          </li>
          <li>
            <i class="fas fa-check text-green-600 mr-2"></i> Confirmation
            immédiate
          </li>
          <li>
            <i class="fas fa-check text-green-600 mr-2"></i> Support client
            24h/24
          </li>
        @endif
      </ul>
    </div>
  </aside>
</main>
