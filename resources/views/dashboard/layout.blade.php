<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard Admin') - Jatsmanor</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap">
    <style>
        body {
            font-family: "Inter", sans-serif;
        }
        .sidebar-gradient {
            background: linear-gradient(135deg, #1e3a8a 80%, #2563eb 100%);
        }
        
        /* Toast animations */
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
        
        .toast-enter {
            animation: slideIn 0.3s ease-out forwards;
        }
        
        .toast-exit {
            animation: slideOut 0.3s ease-in forwards;
        }
        
        /* Loading spinner animation */
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        .fa-spin {
            animation: spin 1s linear infinite;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-100 text-gray-800">
    @include('dashboard.partials.topnav')

    <div class="flex h-[calc(100vh-64px)]">
        @include('dashboard.partials.sidebar')

        <!-- Main content -->
        <main class="flex-1 p-4 md:p-10 overflow-y-auto transition-all duration-300 w-full bg-gray-50" 
              id="main-content" 
              style="margin-left: 0">
            @yield('content')
        </main>
    </div>

    <script>
        // Sidebar responsive toggle
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebarClose = document.getElementById('sidebar-close');
        const sidebarOverlay = document.getElementById('sidebar-overlay');
        const sidebarOverlayClose = document.getElementById('sidebar-overlay-close');
        const mainContent = document.getElementById('main-content');

        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            sidebarOverlay.classList.remove('hidden');
            mainContent.style.marginLeft = '0';
        }
        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            sidebarOverlay.classList.add('hidden');
            mainContent.style.marginLeft = '0';
        }

        sidebarToggle && sidebarToggle.addEventListener('click', openSidebar);
        sidebarClose && sidebarClose.addEventListener('click', closeSidebar);
        sidebarOverlay && sidebarOverlay.addEventListener('click', closeSidebar);
        sidebarOverlayClose && sidebarOverlayClose.addEventListener('click', closeSidebar);

        // On resize, adjust main content margin for desktop/mobile
        function adjustMargin() {
            if (window.innerWidth >= 768) {
                mainContent.style.marginLeft = '0';
            } else {
                mainContent.style.marginLeft = '0';
            }
        }
        window.addEventListener('resize', adjustMargin);
        adjustMargin();

        // Dropdown menu
        const dropdownButton = document.getElementById('dropdown-button');
        const dropdown = document.getElementById('dropdown');
        dropdownButton.addEventListener('click', (e) => {
            e.stopPropagation();
            dropdown.classList.toggle('hidden');
        });
        window.addEventListener('click', (e) => {
            if (!dropdownButton.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
