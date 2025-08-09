<!-- Services grid section -->
<section class="max-w-7xl mx-auto px-4 py-12">
  <h2 class="text-2xl md:text-3xl font-bold text-center mb-8">
    {{ $sectionTitle ?? trans('messages.services.title') }}
  </h2>
  <div class="grid md:grid-cols-3 gap-8">
    @if(isset($services) && count($services) > 0)
      @foreach($services as $service)
        <div class="bg-white rounded-lg shadow p-6 flex flex-col items-center text-center">
          <i class="fas fa-{{ $service['icon'] }} text-3xl text-blue-700 mb-4"></i>
          <h3 class="font-semibold text-lg mb-2">{{ $service['title'] }}</h3>
          <p class="text-sm text-gray-600">
            {{ function_exists('getResidenceDescription') && isset($service) ? getResidenceDescription($service) : $service['description'] }}
          </p>
        </div>
      @endforeach
    @else
      <!-- Default services -->
      <div class="bg-white rounded-lg shadow p-6 flex flex-col items-center text-center">
        <i class="fas fa-utensils text-3xl text-blue-700 mb-4"></i>
        <h3 class="font-semibold text-lg mb-2">{{ trans('messages.services.restaurant_title') }}</h3>
        <p class="text-sm text-gray-600">
          {{ trans('messages.services.restaurant_desc') }}
        </p>
      </div>
      <div class="bg-white rounded-lg shadow p-6 flex flex-col items-center text-center">
        <i class="fas fa-spa text-3xl text-blue-700 mb-4"></i>
        <h3 class="font-semibold text-lg mb-2">{{ trans('messages.services.spa_title') }}</h3>
        <p class="text-sm text-gray-600">
          {{ trans('messages.services.spa_desc') }}
        </p>
      </div>
      <div class="bg-white rounded-lg shadow p-6 flex flex-col items-center text-center">
        <i class="fas fa-swimmer text-3xl text-blue-700 mb-4"></i>
        <h3 class="font-semibold text-lg mb-2">{{ trans('messages.services.pool_title') }}</h3>
        <p class="text-sm text-gray-600">
          {{ trans('messages.services.pool_desc') }}
        </p>
      </div>
      <div class="bg-white rounded-lg shadow p-6 flex flex-col items-center text-center">
        <i class="fas fa-concierge-bell text-3xl text-blue-700 mb-4"></i>
        <h3 class="font-semibold text-lg mb-2">{{ trans('messages.services.concierge_title') }}</h3>
        <p class="text-sm text-gray-600">
          {{ trans('messages.services.concierge_desc') }}
        </p>
      </div>
      <div class="bg-white rounded-lg shadow p-6 flex flex-col items-center text-center">
        <i class="fas fa-car text-3xl text-blue-700 mb-4"></i>
        <h3 class="font-semibold text-lg mb-2">{{ trans('messages.services.parking_title') }}</h3>
        <p class="text-sm text-gray-600">
          {{ trans('messages.services.parking_desc') }}
        </p>
      </div>
      <div class="bg-white rounded-lg shadow p-6 flex flex-col items-center text-center">
        <i class="fas fa-wifi text-3xl text-blue-700 mb-4"></i>
        <h3 class="font-semibold text-lg mb-2">{{ trans('messages.services.wifi_title') }}</h3>
        <p class="text-sm text-gray-600">
          {{ trans('messages.services.wifi_desc') }}
        </p>
      </div>
    @endif
  </div>
</section>
