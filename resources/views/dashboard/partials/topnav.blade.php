<nav class="bg-white shadow px-4 py-3 flex items-center justify-between sticky top-0 z-40">
    <div class="flex items-center gap-3">
        <button id="sidebar-toggle" class="md:hidden text-2xl text-blue-800 focus:outline-none mr-2">
            <i class="fas fa-bars"></i>
        </button>
        <span class="text-xl font-bold text-blue-800 flex items-center gap-2">
            <span class="bg-yellow-500 text-blue-900 rounded-full w-8 h-8 flex items-center justify-center text-sm font-bold">J</span>
            Jatsmanor Admin
        </span>
    </div>
    
    <div class="relative flex items-center gap-2">
        <!-- Badge de notification des messages de contact -->
        @can('view-contact-notifications')
            @if(isset($notifications) && $notifications['contact_messages']['pending'] > 0)
                <a href="{{ route('admin.contact.index') }}" 
                   class="relative p-2 text-gray-600 hover:text-blue-800 transition-colors">
                    <i class="fas fa-envelope text-xl"></i>
                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center animate-pulse">
                        {{ $notifications['contact_messages']['pending'] > 9 ? '9+' : $notifications['contact_messages']['pending'] }}
                    </span>
                    @if($notifications['contact_messages']['urgent'] > 0)
                        <span class="absolute -top-2 -left-2 bg-red-600 text-white text-xs px-1 py-0.5 rounded animate-bounce">
                            {{ $notifications['contact_messages']['urgent'] }} urgent{{ $notifications['contact_messages']['urgent'] > 1 ? 's' : '' }}
                        </span>
                    @endif
                </a>
            @endif
        @endcan
        <button class="sm:inline text-sm text-gray-600 focus:outline-none px-3 py-2 rounded hover:bg-gray-100"
                id="dropdown-button" 
                aria-haspopup="true" 
                aria-expanded="false" 
                type="button">
            {{ Auth::user()->name }} <i class="fas fa-chevron-down ml-1"></i>
        </button>
        @if(Auth::user()->avatar)
            <img src="{{ asset('storage/' . Auth::user()->avatar) }}" 
                 alt="Profil" 
                 class="w-10 h-10 rounded-full border-2 border-blue-800 object-cover">
        @else
            <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white text-sm font-bold border-2 border-blue-800">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
        @endif
        <div id="dropdown" class="hidden absolute right-0 top-12 bg-white shadow-lg rounded py-2 px-4 z-50 min-w-[160px]">
            <a href="{{ route('dashboard.mon-compte') }}" class="block py-2 text-sm hover:text-blue-500">Mon compte</a>
            <a href="{{ route('dashboard.parametres') }}" class="block py-2 text-sm hover:text-blue-500">Paramètres</a>
            <a href="#" class="block py-2 text-sm hover:text-blue-500">Aide</a>
            <hr class="my-2 border-t border-gray-400">
            <form method="POST" action="{{ route('logout') }}" class="block">
                @csrf
                <button type="submit" class="block py-2 text-sm hover:text-blue-500 w-full text-left">Se déconnecter</button>
            </form>
        </div>
    </div>
</nav>
