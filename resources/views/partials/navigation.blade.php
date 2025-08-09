<nav class="bg-white shadow sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
        <h1 class="text-xl font-bold text-blue-800">
            <a href="{{ route('home') }}">{{ setting('site_name', 'Jatsmanor') }}</a>
        </h1>
        
        <!-- Desktop menu -->
        <div class="hidden md:flex space-x-6 text-sm">
            <a href="{{ route('home') }}" class="hover:text-blue-500 {{ request()->routeIs('home') ? 'font-semibold text-blue-500' : '' }}">
                {{ trans('messages.nav.home') }}
            </a>
            <a href="{{ route('residences') }}" class="hover:text-blue-500 {{ request()->routeIs('residences*') ? 'font-semibold text-blue-500' : '' }}">
                {{ trans('messages.nav.residences') }}
            </a>
            <a href="{{ route('services') }}" class="hover:text-blue-500 {{ request()->routeIs('services') ? 'font-semibold text-blue-500' : '' }}">
                {{ trans('messages.nav.services') }}
            </a>
            <a href="{{ route('galerie') }}" class="hover:text-blue-500 {{ request()->routeIs('galerie') ? 'font-semibold text-blue-500' : '' }}">
                {{ trans('messages.nav.gallery') }}
            </a>
            <a href="{{ route('contact') }}" class="hover:text-blue-500 {{ request()->routeIs('contact') ? 'font-semibold text-blue-500' : '' }}">
                {{ trans('messages.nav.contact') }}
            </a>
            @auth
                <a href="{{ route('booking.index') }}" class="hover:text-blue-500 {{ request()->routeIs('booking.*') ? 'font-semibold text-blue-500' : '' }}">
                    {{ trans('messages.nav.my_bookings') }}
                </a>
                <a href="{{ route('dashboard') }}" class="hover:text-blue-500 font-semibold">{{ trans('messages.nav.dashboard') }}</a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="hover:text-blue-500 font-semibold bg-transparent border-none cursor-pointer">
                        {{ trans('messages.nav.logout') }}
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="hover:text-blue-500 font-semibold">{{ trans('messages.nav.login') }}</a>
            @endauth
        </div>
        
        <!-- Actions à droite : Sélecteur de langue + Bouton Réserver -->
        <div class="hidden md:flex items-center space-x-3">
            <!-- Sélecteur de langue -->
           @include('partials.language-selector') 

            <!-- Bouton Réserver -->
            <a href="{{ route('residences') }}" class="bg-yellow-500 hover:bg-yellow-600 text-sm text-white font-semibold px-4 py-2 rounded transition-colors duration-200">
                {{ trans('messages.nav.book_now') }}
            </a>
        </div>
        
        <!-- Mobile menu button -->
        <button id="menu-btn" class="md:hidden text-2xl text-blue-800 focus:outline-none">
            <i class="fas fa-bars"></i>
        </button>
    </div>
    
    <!-- Mobile menu -->
    <div id="mobile-menu" class="md:hidden hidden px-4 pb-4">
        <a href="{{ route('home') }}" class="block py-2 text-sm hover:text-blue-500">{{ trans('messages.nav.home') }}</a>
        <a href="{{ route('residences') }}" class="block py-2 text-sm hover:text-blue-500">{{ trans('messages.nav.residences') }}</a>
        <a href="{{ route('services') }}" class="block py-2 text-sm hover:text-blue-500">{{ trans('messages.nav.services') }}</a>
        <a href="{{ route('galerie') }}" class="block py-2 text-sm hover:text-blue-500">{{ trans('messages.nav.gallery') }}</a>
        <a href="{{ route('contact') }}" class="block py-2 text-sm hover:text-blue-500">{{ trans('messages.nav.contact') }}</a>
        
        @auth
            <a href="{{ route('booking.index') }}" class="block py-2 text-sm hover:text-blue-500 {{ request()->routeIs('booking.*') ? 'font-semibold text-blue-500' : '' }}">{{ trans('messages.nav.my_bookings') }}</a>
            <a href="{{ route('dashboard') }}" class="block py-2 text-sm hover:text-blue-500 font-semibold">{{ trans('messages.nav.dashboard') }}</a>
            <form method="POST" action="{{ route('logout') }}" class="block">
                @csrf
                <button type="submit" class="block py-2 text-sm hover:text-blue-500 font-semibold w-full text-left bg-transparent border-none cursor-pointer">
                    {{ trans('messages.nav.logout') }}
                </button>
            </form>
        @else
            <a href="{{ route('login') }}" class="block py-2 text-sm hover:text-blue-500 font-semibold">{{ trans('messages.nav.login') }}</a>
        @endauth
        
        <!-- Sélecteur de langue mobile -->
        <div class="mt-4 pt-4 border-t border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-2 sm:space-y-0">
                <span class="text-sm font-medium text-gray-700">{{ trans('messages.nav.language') }}</span>
                <div class="flex space-x-1 sm:space-x-2">
                    <button onclick="switchLanguage('fr')" 
                            class="flex items-center px-2 sm:px-3 py-2 rounded-md text-xs sm:text-sm {{ app()->getLocale() === 'fr' ? 'bg-blue-100 text-blue-600 font-medium' : 'text-gray-600 hover:bg-gray-100' }} min-h-[44px] flex-1 sm:flex-initial justify-center">
                        <span class="text-base sm:text-lg mr-1 sm:mr-2">🇫🇷</span>
                        <span class="font-medium">FR</span>
                    </button>
                    <button onclick="switchLanguage('en')" 
                            class="flex items-center px-2 sm:px-3 py-2 rounded-md text-xs sm:text-sm {{ app()->getLocale() === 'en' ? 'bg-blue-100 text-blue-600 font-medium' : 'text-gray-600 hover:bg-gray-100' }} min-h-[44px] flex-1 sm:flex-initial justify-center">
                        <span class="text-base sm:text-lg mr-1 sm:mr-2">🇬🇧</span>
                        <span class="font-medium">EN</span>
                    </button>
                </div>
            </div>
        </div>
        
        <a href="{{ route('residences') }}" class="block bg-yellow-500 hover:bg-yellow-600 text-white font-semibold px-4 py-2 rounded mt-4 text-center">
            {{ trans('messages.nav.book_now') }}
        </a>
    </div>
</nav>