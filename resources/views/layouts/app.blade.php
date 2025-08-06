<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', setting('site_name', 'Jatsmanor') . ' - ' . setting('site_description', 'Résidences Meublées à Abidjan'))</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" />
    
    <style>
        body { font-family: "Inter", sans-serif; }
    </style>
    
    @yield('styles')
</head>
<body class="bg-gray-50 text-gray-800">
    <!-- Header -->
    @include('partials.header')

    <!-- Navigation -->
    @include('partials.navigation')

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    @include('partials.footer')

    <!-- Scripts -->
    @include('partials.scripts')
    
    @yield('scripts')
</body>
</html>