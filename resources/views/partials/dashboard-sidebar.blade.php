<aside id="sidebar" class="sidebar-gradient fixed md:static top-0 left-0 h-full w-72 text-white p-8 space-y-6 z-50 transition-transform duration-300 md:translate-x-0 -translate-x-full">
    <div class="flex items-center justify-between mb-10">
        <h2 class="text-3xl font-bold flex items-center gap-2">
            <span class="bg-blue-800 text-white rounded-full w-8 h-8 flex items-center justify-center text-lg">J</span>
            Jatsmanor
        </h2>
        <button id="sidebar-close" class="md:hidden text-xl">
            <i class="fas fa-times"></i>
        </button>
    </div>
    
    <nav class="flex flex-col space-y-5 text-base font-medium">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-blue-800 transition {{ request()->routeIs('dashboard') ? 'bg-blue-800' : '' }}">
            <i class="fas fa-home"></i> Tableau de bord
        </a>
        <a href="{{ route('dashboard.residences') }}" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-blue-800 transition {{ request()->routeIs('dashboard.residences') ? 'bg-blue-800' : '' }}">
            <i class="fas fa-building"></i> Gérer les résidences
        </a>
        <a href="{{ route('dashboard.chambres') }}" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-blue-800 transition {{ request()->routeIs('dashboard.chambres') ? 'bg-blue-800' : '' }}">
            <i class="fas fa-bed"></i> Gérer les chambres
        </a>
        @can('view-bookings')
        <a href="{{ route('dashboard.reservations') }}" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-blue-800 transition {{ request()->routeIs('dashboard.reservations') ? 'bg-blue-800' : '' }}">
            <i class="fas fa-calendar-alt"></i> Gestion des réservations
        </a>
        @endcan
        
        <!-- Contact Messages Badge -->
        <div class="px-3 py-2">
            @include('partials.contact-badge')
        </div>
        
        <a href="{{ route('dashboard.utilisateurs') }}" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-blue-800 transition {{ request()->routeIs('dashboard.utilisateurs') ? 'bg-blue-800' : '' }}">
            <i class="fas fa-users"></i> Utilisateurs
        </a>
        <a href="{{ route('dashboard.parametres') }}" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-blue-800 transition {{ request()->routeIs('dashboard.parametres') ? 'bg-blue-800' : '' }}">
            <i class="fas fa-cog"></i> Paramètres
        </a>
    </nav>
    
    <!-- Notification Summary -->
    @if(isset($notifications))
    <div class="mt-8 p-4 bg-blue-800 rounded-lg">
        <h3 class="text-sm font-semibold mb-2 flex items-center gap-2">
            <i class="fas fa-bell"></i> Notifications
        </h3>
        <div class="space-y-2 text-sm">
            <div class="flex justify-between">
                <span>Messages non lus:</span>
                <span class="font-semibold">{{ $notifications['contact_messages']['pending'] }}</span>
            </div>
            <div class="flex justify-between">
                <span>Aujourd'hui:</span>
                <span class="font-semibold">{{ $notifications['contact_messages']['today'] }}</span>
            </div>
            <div class="flex justify-between">
                <span>Cette semaine:</span>
                <span class="font-semibold">{{ $notifications['contact_messages']['this_week'] }}</span>
            </div>
            @if($notifications['contact_messages']['urgent'] > 0)
            <div class="flex justify-between text-red-300">
                <span>Urgents (>24h):</span>
                <span class="font-semibold animate-pulse">{{ $notifications['contact_messages']['urgent'] }}</span>
            </div>
            @endif
        </div>
        <div class="text-xs text-blue-300 mt-2">
            Dernière MAJ: {{ $notifications['last_updated'] }}
        </div>
    </div>
    @endif
</aside>