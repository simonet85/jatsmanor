<!-- Rooms Grid -->
<div class="grid md:grid-cols-3 gap-6">
    @if(isset($rooms) && count($rooms) > 0)
        @foreach($rooms as $room)
            @include('partials.room-card', ['room' => $room])
        @endforeach
    @else
        <!-- Default rooms if no data is provided -->
        @include('partials.room-card', [
            'room' => (object)[
                'id' => 1,
                'slug' => 'chambre-single-demo',
                'nom' => 'Chambre Single',
                'description' => 'Lit 1 place, salle de bain privée',
                'prix' => 15000,
                'image' => 'chambre1.jpg',
                'amenities' => [
                    ['icon' => 'fas fa-wifi', 'name' => 'Wi-Fi'],
                    ['icon' => 'fas fa-snowflake', 'name' => 'Climatisation'],
                    ['icon' => 'fas fa-tv', 'name' => 'Télévision'],
                    ['icon' => 'fas fa-broom', 'name' => 'Ménage inclus']
                ],
                'disponible' => true
            ]
        ])
        
        @include('partials.room-card', [
            'room' => (object)[
                'id' => 2,
                'slug' => 'chambre-double-demo',
                'nom' => 'Chambre Double',
                'description' => 'Lit 2 places, balcon, cuisine',
                'prix' => 25000,
                'image' => 'chambre2.jpg',
                'amenities' => [
                    ['icon' => 'fas fa-wifi', 'name' => 'Wi-Fi'],
                    ['icon' => 'fas fa-utensils', 'name' => 'Cuisine équipée'],
                    ['icon' => 'fas fa-tv', 'name' => 'Télévision + Netflix'],
                    ['icon' => 'fas fa-car', 'name' => 'Parking privé']
                ],
                'disponible' => true
            ]
        ])
        
        @include('partials.room-card', [
            'room' => (object)[
                'id' => 3,
                'slug' => 'suite-deluxe-demo',
                'nom' => 'Suite Deluxe',
                'description' => 'Salon privé, grande salle de bain, vue sur jardin',
                'prix' => 40000,
                'image' => 'chambre3.jpg',
                'amenities' => [
                    ['icon' => 'fas fa-tv', 'name' => 'TV écran plat'],
                    ['icon' => 'fas fa-wine-glass', 'name' => 'Mini-bar'],
                    ['icon' => 'fas fa-bell-concierge', 'name' => 'Service de chambre'],
                    ['icon' => 'fas fa-shield-alt', 'name' => 'Gardiennage 24h/24']
                ],
                'disponible' => true
            ]
        ])
    @endif
</div>

@if(isset($showNoResults) && $showNoResults)
    <div class="text-center py-12">
        <div class="bg-gray-100 rounded-lg p-8 max-w-md mx-auto">
            <i class="fas fa-search text-4xl text-gray-400 mb-4"></i>
            <h3 class="text-lg font-semibold text-gray-600 mb-2">Aucun résultat trouvé</h3>
            <p class="text-gray-500 text-sm mb-4">
                Aucune chambre ne correspond à vos critères de recherche.
            </p>
            <a href="{{ route('residences') }}" class="inline-block bg-blue-800 hover:bg-blue-900 text-white px-4 py-2 rounded text-sm transition duration-200">
                Voir toutes les chambres
            </a>
        </div>
    </div>
@endif
