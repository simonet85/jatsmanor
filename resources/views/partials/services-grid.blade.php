<!-- Services grid section -->
<section class="max-w-7xl mx-auto px-4 py-12">
  <h2 class="text-2xl md:text-3xl font-bold text-center mb-8">
    {{ $sectionTitle ?? 'Nos Services' }}
  </h2>
  <div class="grid md:grid-cols-3 gap-8">
    @if(isset($services) && count($services) > 0)
      @foreach($services as $service)
        <div class="bg-white rounded-lg shadow p-6 flex flex-col items-center text-center">
          <i class="fas fa-{{ $service['icon'] }} text-3xl text-blue-700 mb-4"></i>
          <h3 class="font-semibold text-lg mb-2">{{ $service['title'] }}</h3>
          <p class="text-sm text-gray-600">
            {{ $service['description'] }}
          </p>
        </div>
      @endforeach
    @else
      <!-- Default services -->
      <div class="bg-white rounded-lg shadow p-6 flex flex-col items-center text-center">
        <i class="fas fa-utensils text-3xl text-blue-700 mb-4"></i>
        <h3 class="font-semibold text-lg mb-2">Restaurant Gastronomique</h3>
        <p class="text-sm text-gray-600">
          Profitez d'une cuisine raffinée et variée, préparée par nos chefs
          dans un cadre élégant.
        </p>
      </div>
      <div class="bg-white rounded-lg shadow p-6 flex flex-col items-center text-center">
        <i class="fas fa-spa text-3xl text-blue-700 mb-4"></i>
        <h3 class="font-semibold text-lg mb-2">Spa & Bien-être</h3>
        <p class="text-sm text-gray-600">
          Détendez-vous avec nos soins, massages et espace bien-être pour une
          expérience relaxante.
        </p>
      </div>
      <div class="bg-white rounded-lg shadow p-6 flex flex-col items-center text-center">
        <i class="fas fa-swimmer text-3xl text-blue-700 mb-4"></i>
        <h3 class="font-semibold text-lg mb-2">Piscine Infinity</h3>
        <p class="text-sm text-gray-600">
          Profitez de notre piscine à débordement avec vue panoramique sur la
          ville.
        </p>
      </div>
      <div class="bg-white rounded-lg shadow p-6 flex flex-col items-center text-center">
        <i class="fas fa-concierge-bell text-3xl text-blue-700 mb-4"></i>
        <h3 class="font-semibold text-lg mb-2">Service Concierge</h3>
        <p class="text-sm text-gray-600">
          Notre équipe est disponible 24h/24 pour répondre à tous vos besoins
          et demandes spéciales.
        </p>
      </div>
      <div class="bg-white rounded-lg shadow p-6 flex flex-col items-center text-center">
        <i class="fas fa-car text-3xl text-blue-700 mb-4"></i>
        <h3 class="font-semibold text-lg mb-2">Parking Privé</h3>
        <p class="text-sm text-gray-600">
          Un parking sécurisé et réservé à nos clients pour votre
          tranquillité.
        </p>
      </div>
      <div class="bg-white rounded-lg shadow p-6 flex flex-col items-center text-center">
        <i class="fas fa-wifi text-3xl text-blue-700 mb-4"></i>
        <h3 class="font-semibold text-lg mb-2">Wi-Fi Haut Débit</h3>
        <p class="text-sm text-gray-600">
          Restez connecté grâce à notre connexion internet rapide et gratuite
          dans tout l'établissement.
        </p>
      </div>
    @endif
  </div>
</section>
