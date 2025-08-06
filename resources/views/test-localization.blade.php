<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Test de Localisation - Jatsmanor</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <!-- Header avec informations de debug -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h1 class="text-3xl font-bold text-center mb-6">🌍 Test de Localisation Multilingue</h1>
                
                <!-- Informations de debug -->
                <div class="grid md:grid-cols-2 gap-6 mb-6">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-blue-800 mb-2">Informations système</h3>
                        <ul class="text-sm space-y-1">
                            <li><strong>Locale actuelle:</strong> {{ app()->getLocale() }}</li>
                            <li><strong>Locale de session:</strong> {{ session('locale', 'non définie') }}</li>
                            <li><strong>Locales disponibles:</strong> {{ implode(', ', config('app.available_locales', [])) }}</li>
                            <li><strong>Locale par défaut:</strong> {{ config('app.locale') }}</li>
                        </ul>
                    </div>
                    
                    <div class="bg-green-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-green-800 mb-2">Tests des helpers</h3>
                        <ul class="text-sm space-y-1">
                            <li><strong>current_locale():</strong> {{ current_locale() }}</li>
                            <li><strong>locale_name():</strong> {{ locale_name(current_locale()) }}</li>
                            <li><strong>locale_flag():</strong> {{ locale_flag(current_locale()) }}</li>
                        </ul>
                    </div>
                </div>

                <!-- Sélecteur de langue -->
                <div class="text-center mb-8">
                    @include('partials.language-selector')
                </div>
            </div>

            <!-- Test des traductions -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h2 class="text-2xl font-bold mb-6">🔄 Test des Traductions</h2>
                
                <div class="grid md:grid-cols-2 gap-6">
                    <!-- Navigation -->
                    <div class="space-y-3">
                        <h3 class="font-semibold text-gray-800">Navigation</h3>
                        <div class="space-y-1 text-sm">
                            <div><strong>Accueil:</strong> {{ __t('nav.home') }}</div>
                            <div><strong>Résidences:</strong> {{ __t('nav.residences') }}</div>
                            <div><strong>Contact:</strong> {{ __t('nav.contact') }}</div>
                            <div><strong>Réserver:</strong> {{ __t('nav.book_now') }}</div>
                            <div><strong>Langue:</strong> {{ __t('nav.language') }}</div>
                        </div>
                    </div>
                    
                    <!-- Page d'accueil -->
                    <div class="space-y-3">
                        <h3 class="font-semibold text-gray-800">Page d'accueil</h3>
                        <div class="space-y-1 text-sm">
                            <div><strong>Titre hero:</strong> {{ __t('home.hero_title') }}</div>
                            <div><strong>Sous-titre:</strong> {{ __t('home.hero_subtitle') }}</div>
                            <div><strong>Rechercher:</strong> {{ __t('home.search_button') }}</div>
                            <div><strong>Invités:</strong> {{ __t('home.guests') }}</div>
                        </div>
                    </div>
                    
                    <!-- Formulaires -->
                    <div class="space-y-3">
                        <h3 class="font-semibold text-gray-800">Formulaires</h3>
                        <div class="space-y-1 text-sm">
                            <div><strong>Nom:</strong> {{ __t('forms.name') }}</div>
                            <div><strong>Email:</strong> {{ __t('forms.email') }}</div>
                            <div><strong>Envoyer:</strong> {{ __t('forms.send') }}</div>
                            <div><strong>Sauvegarder:</strong> {{ __t('forms.save') }}</div>
                        </div>
                    </div>
                    
                    <!-- Système -->
                    <div class="space-y-3">
                        <h3 class="font-semibold text-gray-800">Messages système</h3>
                        <div class="space-y-1 text-sm">
                            <div><strong>Bienvenue:</strong> {{ __t('system.welcome') }}</div>
                            <div><strong>Succès:</strong> {{ __t('system.success') }}</div>
                            <div><strong>Erreur:</strong> {{ __t('system.error') }}</div>
                            <div><strong>Chargement:</strong> {{ __t('system.loading') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Test des URLs avec locale -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h2 class="text-2xl font-bold mb-6">🔗 Test des URLs avec Locale</h2>
                
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                        <span>Accueil (Français)</span>
                        <a href="{{ route('home', ['lang' => 'fr']) }}" class="text-blue-600 hover:underline">
                            {{ route('home', ['lang' => 'fr']) }}
                        </a>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                        <span>Accueil (Anglais)</span>
                        <a href="{{ route('home', ['lang' => 'en']) }}" class="text-blue-600 hover:underline">
                            {{ route('home', ['lang' => 'en']) }}
                        </a>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                        <span>Résidences (Français)</span>
                        <a href="{{ route('residences', ['lang' => 'fr']) }}" class="text-blue-600 hover:underline">
                            {{ route('residences', ['lang' => 'fr']) }}
                        </a>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                        <span>Résidences (Anglais)</span>
                        <a href="{{ route('residences', ['lang' => 'en']) }}" class="text-blue-600 hover:underline">
                            {{ route('residences', ['lang' => 'en']) }}
                        </a>
                    </div>
                </div>
            </div>

            <!-- Boutons de test -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold mb-6">🧪 Actions de Test</h2>
                
                <div class="grid md:grid-cols-3 gap-4">
                    <button onclick="testLocaleAPI()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition-colors">
                        Test API Locale
                    </button>
                    <button onclick="testTranslationAPI()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded transition-colors">
                        Test API Traductions
                    </button>
                    <button onclick="testSessionStorage()" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded transition-colors">
                        Test Session Storage
                    </button>
                </div>
                
                <!-- Zone de résultats -->
                <div id="test-results" class="mt-6 p-4 bg-gray-100 rounded-lg hidden">
                    <h3 class="font-semibold mb-2">Résultats des tests :</h3>
                    <pre id="test-output" class="text-sm text-gray-700 whitespace-pre-wrap"></pre>
                </div>
            </div>

            <!-- Retour à l'accueil -->
            <div class="text-center mt-8">
                <a href="{{ route('home') }}" class="inline-flex items-center bg-yellow-500 hover:bg-yellow-600 text-white font-semibold px-6 py-3 rounded-lg transition-colors">
                    <i class="fas fa-home mr-2"></i>
                    Retour à l'accueil
                </a>
            </div>
        </div>
    </div>

    <script>
        // Tests JavaScript
        function testLocaleAPI() {
            fetch('{{ route("language.current") }}')
                .then(response => response.json())
                .then(data => {
                    displayTestResult('Test API Locale', data);
                })
                .catch(error => {
                    displayTestResult('Test API Locale', { error: error.message });
                });
        }

        function testTranslationAPI() {
            fetch('{{ route("language.translations") }}')
                .then(response => response.json())
                .then(data => {
                    displayTestResult('Test API Traductions', {
                        locale: data.locale,
                        sample_translations: {
                            'nav.home': data.translations?.nav?.home,
                            'home.hero_title': data.translations?.home?.hero_title,
                            'forms.name': data.translations?.forms?.name
                        }
                    });
                })
                .catch(error => {
                    displayTestResult('Test API Traductions', { error: error.message });
                });
        }

        function testSessionStorage() {
            const result = {
                current_locale: '{{ current_locale() }}',
                session_locale: '{{ session("locale") }}',
                browser_language: navigator.language,
                available_locales: @json(config('app.available_locales'))
            };
            displayTestResult('Test Session Storage', result);
        }

        function displayTestResult(testName, result) {
            const resultsDiv = document.getElementById('test-results');
            const outputPre = document.getElementById('test-output');
            
            resultsDiv.classList.remove('hidden');
            outputPre.textContent = `${testName}:\n${JSON.stringify(result, null, 2)}`;
        }
    </script>
</body>
</html>
