<!-- Main residence details content with translation support -->
<main class="max-w-7xl mx-auto px-4 py-8 grid md:grid-cols-2 gap-10">
  <!-- Images & infos principales -->
  <div>
    <div class="mb-6">
      @if(isset($residence) && is_object($residence))
        @if($residence->image)
          <!-- Image principale de la résidence -->
          <img
            src="{{ asset($residence->image) }}?v={{ time() }}"
            alt="{{ getResidenceName($residence) }}"
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
            alt="{{ getResidenceName($residence) }}"
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
          src="{{ $residence['image'] ?? asset('img/chambre1.png') }}"
          alt="{{ function_exists('getResidenceName') ? getResidenceName($residence) : ($residence['name'] ?? __('messages.room.single')) }}"
          class="w-full h-64 object-cover rounded-lg shadow"
        />
      @endif
    </div>
    
    <!-- Translation Language Indicator -->
    @if(isset($residence) && is_object($residence) && app()->getLocale() === 'en')
      <div class="mb-2 flex items-center gap-2">
        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
          <i class="fas fa-language mr-1"></i>
          @if($residence->name_en)
            {{ __('Translated with DeepL API') }}
          @else
            {{ __('French content') }}
          @endif
        </span>
      </div>
    @endif
    
    <h2 class="text-2xl font-bold mb-2">
      @if(isset($residence) && is_object($residence))
        {{ getResidenceName($residence) }}
      @else
        {{ function_exists('getResidenceName') ? getResidenceName($residence) : ($residence['name'] ?? __('messages.room.single')) }}
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
          {{ app()->getLocale() === 'en' && !empty($residence->location_en) ? $residence->location_en : $residence->location }}
        @else
          {{ $residence['location'] ?? 'Cocody, 1er étage' }}
        @endif
      </span>
    </div>
    
    <p class="text-gray-700 mb-4">
      @if(isset($residence) && is_object($residence))
        {{ app()->getLocale() === 'en' && !empty($residence->description_en) ? $residence->description_en : getResidenceDescription($residence) }}
      @else
        {{ function_exists('getResidenceDescription') ? getResidenceDescription($residence) : ($residence['description'] ?? __('messages.room.default_description')) }}
      @endif
    </p>

    <!-- Short description if different from main description -->
    @if(isset($residence) && is_object($residence) && getResidenceShortDescription($residence))
      <div class="bg-gray-50 rounded-lg p-3 mb-4">
        <h3 class="font-semibold text-sm text-gray-700 mb-1">{{ __('Quick Overview') }}</h3>
        <p class="text-sm text-gray-600">{{ getResidenceShortDescription($residence) }}</p>
      </div>
    @endif
    
    <ul class="flex flex-wrap gap-3 text-sm mb-4">
      @if(isset($residence) && is_object($residence) && $residence->amenities->count() > 0)
        @foreach($residence->amenities as $amenity)
          <li class="flex items-center gap-1 bg-gray-100 px-2 py-1 rounded">
            <i class="fas fa-check text-green-500 text-xs"></i>
            {{ $amenity->name }}
          </li>
        @endforeach
      @else
        <li class="flex items-center gap-1 bg-gray-100 px-2 py-1 rounded">
          <i class="fas fa-check text-green-500 text-xs"></i>
          {{ __('Private bathroom') }}
        </li>
        <li class="flex items-center gap-1 bg-gray-100 px-2 py-1 rounded">
          <i class="fas fa-check text-green-500 text-xs"></i>
          {{ __('Free WiFi') }}
        </li>
        <li class="flex items-center gap-1 bg-gray-100 px-2 py-1 rounded">
          <i class="fas fa-check text-green-500 text-xs"></i>
          {{ __('Air conditioning') }}
        </li>
        <li class="flex items-center gap-1 bg-gray-100 px-2 py-1 rounded">
          <i class="fas fa-check text-green-500 text-xs"></i>
          {{ __('Secure access') }}
        </li>
      @endif
    </ul>

    <!-- Language switcher for testing -->
    <div class="mb-4 p-3 bg-blue-50 rounded-lg">
      <h4 class="font-semibold text-sm text-blue-800 mb-2">{{ __('Language / Langue') }}</h4>
      <div class="flex gap-2">
        <a href="{{ request()->fullUrlWithQuery(['lang' => 'fr']) }}" 
           class="px-3 py-1 rounded text-sm {{ app()->getLocale() === 'fr' ? 'bg-blue-600 text-white' : 'bg-white text-blue-600 border border-blue-300' }}">
          🇫🇷 Français
        </a>
        <a href="{{ request()->fullUrlWithQuery(['lang' => 'en']) }}" 
           class="px-3 py-1 rounded text-sm {{ app()->getLocale() === 'en' ? 'bg-blue-600 text-white' : 'bg-white text-blue-600 border border-blue-300' }}">
          🇬🇧 English
        </a>
      </div>
    </div>
  </div>

  <!-- Rest of the content continues as before... -->
  <div>
    <!-- Pricing and booking section -->
    <div class="bg-white p-6 rounded-lg shadow-lg sticky top-8">
      <div class="text-center mb-4">
        <div class="text-3xl font-bold text-gray-900">
          @if(isset($residence) && is_object($residence))
            {{ format_fcfa($residence->price_per_night) }}
          @else
            {{ format_fcfa($residence['price'] ?? 25000) }}
          @endif
        </div>
        <div class="text-sm text-gray-500">{{ __('per night') }}</div>
      </div>
      
      <!-- Rest of booking form... -->
      <div class="space-y-4">
        <button class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-blue-700 transition">
          {{ __('Book Now') }}
        </button>
      </div>
    </div>
  </div>
</main>

<!-- Debug info for development (remove in production) -->
@if(config('app.debug'))
<div class="max-w-7xl mx-auto px-4 py-4">
  <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
    <h3 class="font-semibold text-yellow-800 mb-2">🔧 Debug: Translation Info</h3>
    @if(isset($residence) && is_object($residence))
      <div class="text-xs space-y-1 text-yellow-700">
        <div><strong>Current Locale:</strong> {{ app()->getLocale() }}</div>
        <div><strong>Name FR:</strong> {{ $residence->name ?? 'N/A' }}</div>
        <div><strong>Name EN:</strong> {{ $residence->name_en ?? 'N/A' }}</div>
        <div><strong>Description FR:</strong> {{ Str::limit($residence->description ?? 'N/A', 100) }}</div>
        <div><strong>Description EN:</strong> {{ Str::limit($residence->description_en ?? 'N/A', 100) }}</div>
        <div><strong>Using Translation:</strong> {{ app()->getLocale() === 'en' && $residence->name_en ? 'Yes' : 'No' }}</div>
      </div>
    @endif
  </div>
</div>
@endif
