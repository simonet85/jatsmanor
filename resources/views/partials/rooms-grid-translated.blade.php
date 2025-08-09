<!-- Rooms/Residences grid with translation support -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($residences ?? $rooms ?? [] as $residence)
        <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 
                    residence-card border border-gray-200" 
             data-price="{{ $residence->price_per_night ?? $residence->price ?? 0 }}"
             data-type="{{ $residence->type ?? 'residence' }}"
             data-location="{{ $residence->location ?? '' }}">
            
            <!-- Image -->
            <div class="relative h-48 overflow-hidden">
                @if($residence->image)
                    <img src="{{ asset($residence->image) }}?v={{ time() }}" 
                         alt="{{ getResidenceName($residence) }}"
                         class="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
                         loading="lazy"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="w-full h-full bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center" style="display: none;">
                        <i class="fas fa-home text-blue-400 text-4xl"></i>
                    </div>
                @elseif(isset($residence->images) && $residence->images->count() > 0)
                    <img src="{{ asset('storage/' . $residence->images->first()->image_path) }}?v={{ time() }}" 
                         alt="{{ getResidenceName($residence) }}"
                         class="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
                         loading="lazy"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="w-full h-full bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center" style="display: none;">
                        <i class="fas fa-home text-blue-400 text-4xl"></i>
                    </div>
                @else
                    <div class="w-full h-full bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center">
                        <i class="fas fa-home text-blue-400 text-4xl"></i>
                    </div>
                @endif

                <!-- Translation indicator -->
                @if(app()->getLocale() === 'en' && isset($residence->name_en) && $residence->name_en)
                    <div class="absolute top-2 right-2">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-500 text-white shadow-sm">
                            <i class="fas fa-language mr-1"></i>
                            EN
                        </span>
                    </div>
                @endif

                <!-- Featured badge -->
                @if(isset($residence->is_featured) && $residence->is_featured)
                    <div class="absolute top-2 left-2">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-500 text-white shadow-sm">
                            <i class="fas fa-star mr-1"></i>
                            {{ __('Featured') }}
                        </span>
                    </div>
                @endif

                <!-- Rating -->
                <div class="absolute bottom-2 left-2">
                    <span class="bg-black bg-opacity-60 text-white px-2 py-1 rounded text-xs flex items-center gap-1">
                        <i class="fas fa-star text-yellow-400"></i>
                        {{ number_format($residence->rating ?? 4.5, 1) }}
                    </span>
                </div>
            </div>

            <!-- Content -->
            <div class="p-4">
                <!-- Title with translation -->
                <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">
                    {{ getResidenceName($residence) }}
                </h3>

                <!-- Location -->
                <div class="flex items-center text-sm text-gray-600 mb-2">
                    <i class="fas fa-map-marker-alt mr-1"></i>
                    {{ $residence->location ?? 'Abidjan' }}
                </div>

                <!-- Short description with translation -->
                @if(getResidenceShortDescription($residence))
                    <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                        {{ getResidenceShortDescription($residence) }}
                    </p>
                @endif

                <!-- Amenities -->
                <div class="flex flex-wrap gap-1 mb-3">
                    @if(isset($residence->amenities) && $residence->amenities->count() > 0)
                        @foreach($residence->amenities->take(3) as $amenity)
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs bg-gray-100 text-gray-700">
                                {{ $amenity->name }}
                            </span>
                        @endforeach
                        @if($residence->amenities->count() > 3)
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs bg-gray-100 text-gray-700">
                                +{{ $residence->amenities->count() - 3 }} {{ __('more') }}
                            </span>
                        @endif
                    @else
                        <span class="inline-flex items-center px-2 py-1 rounded text-xs bg-gray-100 text-gray-700">
                            <i class="fas fa-wifi mr-1"></i>
                            {{ __('WiFi') }}
                        </span>
                        <span class="inline-flex items-center px-2 py-1 rounded text-xs bg-gray-100 text-gray-700">
                            <i class="fas fa-snowflake mr-1"></i>
                            {{ __('AC') }}
                        </span>
                    @endif
                </div>

                <!-- Price and action -->
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-xl font-bold text-gray-900">
                            {{ format_fcfa($residence->price_per_night ?? $residence->price ?? 25000) }}
                        </span>
                        <span class="text-sm text-gray-500">{{ __('per night') }}</span>
                    </div>
                    
                    <a href="{{ route('residence.details', $residence->slug ?? $residence->id) }}" 
                       class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                        {{ __('View Details') }}
                    </a>
                </div>

                <!-- Translation debug info (only in development) -->
                @if(config('app.debug') && app()->getLocale() === 'en')
                    <div class="mt-2 pt-2 border-t border-gray-200">
                        <div class="text-xs text-gray-500">
                            <strong>Debug:</strong>
                            {{ $residence->name_en ? 'Using EN translation' : 'Using FR original' }}
                            @if($residence->name_en)
                                <br><strong>FR:</strong> {{ Str::limit($residence->name, 30) }}
                                <br><strong>EN:</strong> {{ Str::limit($residence->name_en, 30) }}
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @empty
        <div class="col-span-full text-center py-12">
            <div class="text-gray-400 mb-4">
                <i class="fas fa-home text-6xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">
                {{ __('No residences found') }}
            </h3>
            <p class="text-gray-600">
                {{ __('Try adjusting your search criteria') }}
            </p>
        </div>
    @endforelse
</div>

<!-- Language switcher for the listing page -->
<div class="mt-8 text-center">
    <div class="inline-flex bg-white rounded-lg shadow-sm border border-gray-200 p-1">
        <a href="{{ request()->fullUrlWithQuery(['lang' => 'fr']) }}" 
           class="px-4 py-2 rounded-md text-sm font-medium transition-colors {{ app()->getLocale() === 'fr' ? 'bg-blue-600 text-white shadow-sm' : 'text-gray-700 hover:text-gray-900' }}">
            🇫🇷 Français
        </a>
        <a href="{{ request()->fullUrlWithQuery(['lang' => 'en']) }}" 
           class="px-4 py-2 rounded-md text-sm font-medium transition-colors {{ app()->getLocale() === 'en' ? 'bg-blue-600 text-white shadow-sm' : 'text-gray-700 hover:text-gray-900' }}">
            🇬🇧 English
        </a>
    </div>
    <p class="text-xs text-gray-500 mt-2">
        {{ __('Content automatically translated using DeepL API') }}
    </p>
</div>
