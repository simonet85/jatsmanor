<aside
    id="sidebar"
    class="sidebar-gradient fixed md:static top-0 left-0 h-full w-72 text-white p-8 space-y-6 z-50 transition-transform duration-300 md:translate-x-0 -translate-x-full"
>
    <div class="flex items-center justify-between mb-10">
        <h2 class="text-3xl font-bold flex items-center gap-2">
            @if(auth()->user()->avatar)
                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" 
                     alt="Profil" 
                     class="w-10 h-10 rounded-full object-cover border-2 border-yellow-500">
            @else
                <div class="bg-yellow-500 text-blue-900 rounded-full w-10 h-10 flex items-center justify-center text-xl font-bold border-2 border-yellow-500">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
            @endif
            @if(auth()->user()->hasRole('Administrator'))
                Admin
            @else
                Client
            @endif
        </h2>
        <button id="sidebar-close" class="md:hidden text-xl">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <nav class="flex flex-col space-y-5 text-base font-medium">
        @if(auth()->user()->hasRole('Administrator'))
            <!-- Menu pour les Administrateurs -->
            <a href="{{ route('dashboard') }}" 
               class="flex items-center gap-3 px-3 py-2 rounded hover:bg-blue-800 transition {{ request()->routeIs('dashboard') ? 'bg-blue-800' : '' }}">
                <i class="fas fa-home"></i> Tableau de bord
            </a>
            <a href="{{ route('dashboard.residences') }}" 
               class="flex items-center gap-3 px-3 py-2 rounded hover:bg-blue-800 transition {{ request()->routeIs('dashboard.residences') || request()->routeIs('admin.residences.*') ? 'bg-blue-800' : '' }}">
                <i class="fas fa-building"></i> Gérer les résidences
            </a>
            <a href="{{ route('dashboard.amenities') }}" 
               class="flex items-center gap-3 px-3 py-2 rounded hover:bg-blue-800 transition {{ request()->routeIs('dashboard.amenities') || request()->routeIs('admin.amenities.*') ? 'bg-blue-800' : '' }}">
                <i class="fas fa-wifi"></i> Gérer les équipements
            </a>
   {{--            <a href="{{ route('dashboard.chambres') }}" 
               class="flex items-center gap-3 px-3 py-2 rounded hover:bg-blue-800 transition {{ request()->routeIs('dashboard.chambres') ? 'bg-blue-800' : '' }}">
                <i class="fas fa-bed"></i> Gérer les chambres
            </a> --}}
            <a href="{{ route('dashboard.reservations') }}" 
               class="flex items-center gap-3 px-3 py-2 rounded hover:bg-blue-800 transition {{ request()->routeIs('dashboard.reservations') || request()->routeIs('admin.bookings.*') ? 'bg-blue-800' : '' }}">
                <i class="fas fa-calendar-alt"></i> Réservations
            </a>
            <a href="{{ route('dashboard.utilisateurs') }}" 
               class="flex items-center gap-3 px-3 py-2 rounded hover:bg-blue-800 transition {{ request()->routeIs('dashboard.utilisateurs') || request()->routeIs('admin.users.*') ? 'bg-blue-800' : '' }}">
                <i class="fas fa-users"></i> Utilisateurs
            </a>
            <a href="{{ route('dashboard.parametres') }}" 
               class="flex items-center gap-3 px-3 py-2 rounded hover:bg-blue-800 transition {{ request()->routeIs('dashboard.parametres') ? 'bg-blue-800' : '' }}">
                <i class="fas fa-cog"></i> Paramètres
            </a>
            <a href="{{ route('dashboard.mon-compte') }}" 
               class="flex items-center gap-3 px-3 py-2 rounded hover:bg-blue-800 transition {{ request()->routeIs('dashboard.mon-compte') ? 'bg-blue-800' : '' }}">
                <i class="fas fa-user"></i> Mon compte
            </a>
        @else
            <!-- Menu pour les Clients -->
            <a href="{{ route('client.bookings.index') }}" 
               class="flex items-center gap-3 px-3 py-2 rounded hover:bg-blue-800 transition {{ request()->routeIs('client.bookings.*') ? 'bg-blue-800' : '' }}">
                <i class="fas fa-calendar-alt"></i> Mes réservations
            </a>
            <a href="{{ route('residences') }}" 
               class="flex items-center gap-3 px-3 py-2 rounded hover:bg-blue-800 transition">
                <i class="fas fa-search"></i> Découvrir les résidences
            </a>
            <a href="{{ route('dashboard.mon-compte') }}" 
               class="flex items-center gap-3 px-3 py-2 rounded hover:bg-blue-800 transition {{ request()->routeIs('dashboard.mon-compte') || request()->routeIs('profile.*') ? 'bg-blue-800' : '' }}">
                <i class="fas fa-user"></i> Mon compte
            </a>
        @endif
    </nav>
</aside>

<!-- Overlay for mobile sidebar -->
<div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-40 z-40 hidden md:hidden">
    <button id="sidebar-overlay-close" class="absolute top-4 right-4 bg-white text-blue-900 rounded-full p-2 shadow focus:outline-none">
        <i class="fas fa-times text-xl"></i>
    </button>
</div>
