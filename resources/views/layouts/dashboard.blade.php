<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') | {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        .sidebar-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .notification-badge {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        
        .urgent-notification {
            animation: urgent-pulse 1s infinite;
        }
        
        @keyframes urgent-pulse {
            0%, 100% { 
                transform: scale(1);
                background-color: #ef4444;
            }
            50% { 
                transform: scale(1.05);
                background-color: #dc2626;
            }
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        @include('partials.dashboard-sidebar')

        <!-- Mobile overlay -->
        <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 md:hidden hidden"></div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Top Bar -->
            <header class="bg-white shadow-sm border-b border-gray-200 px-6 py-4">
                <div class="flex items-center justify-between">
                    <!-- Mobile menu button -->
                    <button id="sidebar-toggle" class="md:hidden text-gray-600 hover:text-gray-800">
                        <i class="fas fa-bars text-xl"></i>
                    </button>

                    <!-- Page Title -->
                    <div class="flex-1 ml-4 md:ml-0">
                        <h1 class="text-2xl font-bold text-gray-800">@yield('title', 'Dashboard')</h1>
                        @hasSection('subtitle')
                            <p class="text-gray-600 text-sm mt-1">@yield('subtitle')</p>
                        @endif
                    </div>

                    <!-- Top Bar Notifications -->
                    <div class="flex items-center gap-4">
                        <!-- Contact Messages Header Badge -->
                        @include('partials.header-contact-badge')

                        @can('view-contact-notifications')
                            @if(isset($notifications))
                                <!-- Quick Stats Dropdown -->
                                <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="p-2 text-gray-600 hover:text-gray-800 transition-colors">
                                    <i class="fas fa-chart-line text-xl"></i>
                                </button>
                                
                                <div x-show="open" @click.outside="open = false" 
                                     class="absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-lg border border-gray-200 z-50"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="opacity-100 scale-100"
                                     x-transition:leave-end="opacity-0 scale-95">
                                    <div class="p-4">
                                        <h3 class="font-semibold text-gray-800 mb-3">Notifications Aujourd'hui</h3>
                                        <div class="space-y-2 text-sm">
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Messages reçus:</span>
                                                <span class="font-semibold">{{ $notifications['contact_messages']['today'] }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Non traités:</span>
                                                <span class="font-semibold text-yellow-600">{{ $notifications['contact_messages']['pending'] }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Cette semaine:</span>
                                                <span class="font-semibold text-blue-600">{{ $notifications['contact_messages']['this_week'] }}</span>
                                            </div>
                                        </div>
                                        <div class="mt-3 pt-3 border-t border-gray-200">
                                            <a href="{{ route('admin.contact.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                Voir tous les messages →
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @endcan

                        <!-- User Menu -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center gap-2 p-2 text-gray-600 hover:text-gray-800 transition-colors">
                                <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>
                            
                            <div x-show="open" @click.outside="open = false" 
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                                <div class="p-2">
                                    <a href="{{ route('dashboard.mon-compte') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded">
                                        <i class="fas fa-user mr-2"></i>Mon Compte
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded">
                                            <i class="fas fa-sign-out-alt mr-2"></i>Déconnexion
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-auto">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div id="flash-message" class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
            <div class="flex items-center gap-2">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div id="flash-message" class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
            <div class="flex items-center gap-2">
                <i class="fas fa-exclamation-circle"></i>
                {{ session('error') }}
            </div>
        </div>
    @endif

    <!-- Scripts -->
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        // Mobile sidebar toggle
        document.getElementById('sidebar-toggle').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        });

        // Close sidebar when clicking overlay
        document.getElementById('sidebar-overlay').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        });

        // Close sidebar when clicking close button
        const sidebarClose = document.getElementById('sidebar-close');
        if (sidebarClose) {
            sidebarClose.addEventListener('click', function() {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('sidebar-overlay');
                
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            });
        }

        // Auto-hide flash messages
        const flashMessage = document.getElementById('flash-message');
        if (flashMessage) {
            setTimeout(() => {
                flashMessage.style.transition = 'opacity 0.5s ease-out, transform 0.5s ease-out';
                flashMessage.style.opacity = '0';
                flashMessage.style.transform = 'translateY(-20px)';
                setTimeout(() => {
                    flashMessage.remove();
                }, 500);
            }, 4000);
        }

        // Auto-refresh notifications every 2 minutes
        setInterval(() => {
            // Only refresh if user is on admin pages
            if (window.location.pathname.includes('/dashboard') || window.location.pathname.includes('/admin')) {
                // You can implement AJAX refresh here if needed
                // For now, we rely on the cache system
            }
        }, 120000); // 2 minutes
    </script>

    @stack('scripts')
</body>
</html>
