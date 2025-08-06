<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Debug Localisation - {{ config('app.name', 'Laravel') }}</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .debug-item { margin: 10px 0; padding: 10px; border: 1px solid #ddd; }
        .success { color: green; }
        .error { color: red; }
    </style>
</head>
<body>
    <h1>🔍 Debug Localisation</h1>
    
    <div class="debug-item">
        <strong>Locale actuelle:</strong> <span class="success">{{ app()->getLocale() }}</span>
    </div>
    
    <div class="debug-item">
        <strong>URL actuelle:</strong> {{ url()->current() }}
    </div>
    
    <div class="debug-item">
        <strong>Session langue:</strong> {{ session('locale', 'non définie') }}
    </div>
    
    <div class="debug-item">
        <strong>Test trans() simple:</strong> 
        <span class="success">{{ trans('messages.nav.home') }}</span>
    </div>
    
    <div class="debug-item">
        <strong>Test témoignage FR:</strong> 
        <span class="success">{{ trans('messages.home.testimonial_1') }}</span>
    </div>
    
    @php
        $currentLocale = app()->getLocale();
        app()->setLocale('en');
    @endphp
    
    <div class="debug-item">
        <strong>Test témoignage EN:</strong> 
        <span class="success">{{ trans('messages.home.testimonial_1') }}</span>
    </div>
    
    @php
        app()->setLocale($currentLocale);
    @endphp
    
    <div class="debug-item">
        <strong>Middleware appliqué:</strong> 
        @if(in_array('localization', request()->route()->gatherMiddleware()))
            <span class="success">✅ OUI</span>
        @else
            <span class="error">❌ NON</span>
        @endif
    </div>
    
    <h2>Tests de switching</h2>
    <a href="{{ route('language.switch', 'fr') }}" onclick="event.preventDefault(); fetch(this.href, {method: 'POST', headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}}).then(() => location.reload());">🇫🇷 Français</a> |
    <a href="{{ route('language.switch', 'en') }}" onclick="event.preventDefault(); fetch(this.href, {method: 'POST', headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}}).then(() => location.reload());">🇬🇧 English</a>
    
    <p><a href="{{ route('home') }}">← Retour à l'accueil</a></p>
</body>
</html>
