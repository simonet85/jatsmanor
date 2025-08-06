<!-- Similar residences section -->
<section class="max-w-7xl mx-auto px-4 py-8">
  <h3 class="text-xl font-bold mb-6">{{ $sectionTitle ?? 'Résidences similaires' }}</h3>
  <div class="grid md:grid-cols-3 gap-6">
    @if(isset($similarResidences) && $similarResidences->count() > 0)
      @foreach($similarResidences as $residence)
        <div class="bg-white shadow rounded overflow-hidden">
          @if($residence->images->count() > 0)
            <img
              src="{{ asset('storage/' . $residence->images->first()->image_path) }}"
              alt="{{ $residence->name }}"
              class="w-full h-40 object-cover"
            />
          @elseif($residence->image)
            <img
              src="{{ asset($residence->image) }}"
              alt="{{ $residence->name }}"
              class="w-full h-40 object-cover"
            />
          @else
            <div class="w-full h-40 bg-gray-200 flex items-center justify-center">
              <i class="fas fa-home text-gray-400 text-2xl"></i>
            </div>
          @endif
          
          <div class="p-4">
            <h4 class="text-lg font-semibold mb-1">{{ $residence->name }}</h4>
            <p class="text-sm text-gray-600 mb-2">{{ Str::limit($residence->short_description ?? $residence->description, 80) }}</p>
            
            @if($residence->amenities->count() > 0)
              <ul class="flex flex-wrap gap-2 text-xs text-gray-700 mb-3">
                @foreach($residence->amenities->take(3) as $amenity)
                  <li class="flex items-center gap-1">
                    <i class="{{ $amenity->icon ?? 'fas fa-check' }} text-blue-700"></i> {{ $amenity->name }}
                  </li>
                @endforeach
              </ul>
            @endif
            
            <p class="text-blue-700 font-bold mb-2">{{ format_fcfa_per_night($residence->price_per_night) }}</p>
            
            <a
              href="{{ route('residence.details', $residence) }}"
              class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded text-sm"
            >
              Voir la fiche
            </a>
          </div>
        </div>
      @endforeach
    @else
      <!-- Default similar residences -->
      <div class="bg-white shadow rounded overflow-hidden">
        <img
          src="{{ asset('images/residences/chambre2.jpg') }}"
          alt="Chambre Double"
          class="w-full h-40 object-cover"
        />
        <div class="p-4">
          <h4 class="text-lg font-semibold mb-1">Chambre Double</h4>
          <p class="text-sm text-gray-600 mb-2">
            Lit 2 places, balcon, cuisine
          </p>
          <ul class="flex flex-wrap gap-2 text-xs text-gray-700 mb-3">
            <li class="flex items-center gap-1">
              <i class="fas fa-wifi text-blue-700"></i> Wi-Fi
            </li>
            <li class="flex items-center gap-1">
              <i class="fas fa-utensils text-blue-700"></i> Cuisine équipée
            </li>
            <li class="flex items-center gap-1">
              <i class="fas fa-tv text-blue-700"></i> Télévision + Netflix
            </li>
          </ul>
          <p class="text-blue-700 font-bold mb-2">25.000 FCFA / nuit</p>
          <a
            href="#"
            class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded text-sm"
          >
            Voir la fiche
          </a>
        </div>
      </div>
      
      <div class="bg-white shadow rounded overflow-hidden">
        <img
          src="{{ asset('images/residences/chambre3.jpg') }}"
          alt="Suite Deluxe"
          class="w-full h-40 object-cover"
        />
        <div class="p-4">
          <h4 class="text-lg font-semibold mb-1">Suite Deluxe</h4>
          <p class="text-sm text-gray-600 mb-2">
            Salon privé, grande salle de bain, vue sur jardin
          </p>
          <ul class="flex flex-wrap gap-2 text-xs text-gray-700 mb-3">
            <li class="flex items-center gap-1">
              <i class="fas fa-tv text-blue-700"></i> TV écran plat
            </li>
            <li class="flex items-center gap-1">
              <i class="fas fa-wine-glass text-blue-700"></i> Mini-bar
            </li>
            <li class="flex items-center gap-1">
              <i class="fas fa-bell-concierge text-blue-700"></i> Service de
              chambre
            </li>
          </ul>
          <p class="text-blue-700 font-bold mb-2">40.000 FCFA / nuit</p>
          <a
            href="#"
            class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded text-sm"
          >
            Voir la fiche
          </a>
        </div>
      </div>

      <div class="bg-white shadow rounded overflow-hidden">
        <img
          src="{{ asset('images/residences/chambre4.jpg') }}"
          alt="Studio Confort"
          class="w-full h-40 object-cover"
        />
        <div class="p-4">
          <h4 class="text-lg font-semibold mb-1">Studio Confort</h4>
          <p class="text-sm text-gray-600 mb-2">
            Lit double, kitchenette, espace bureau
          </p>
          <ul class="flex flex-wrap gap-2 text-xs text-gray-700 mb-3">
            <li class="flex items-center gap-1">
              <i class="fas fa-wifi text-blue-700"></i> Wi-Fi
            </li>
            <li class="flex items-center gap-1">
              <i class="fas fa-laptop text-blue-700"></i> Bureau
            </li>
            <li class="flex items-center gap-1">
              <i class="fas fa-coffee text-blue-700"></i> Machine à café
            </li>
          </ul>
          <p class="text-blue-700 font-bold mb-2">20.000 FCFA / nuit</p>
          <a
            href="#"
            class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded text-sm"
          >
            Voir la fiche
          </a>
        </div>
      </div>
    @endif
  </div>
</section>
