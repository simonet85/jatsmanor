@extends('dashboard.layout')

@section('title', 'Test des Traductions')

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Test des Traductions DeepL</h1>
            <p class="mt-2 text-sm text-gray-600">Vérifiez le bon fonctionnement de l'API DeepL et des traductions automatiques.</p>
        </div>
    </div>

    <!-- Status DeepL -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-semibold mb-4">Status de l'API DeepL</h2>
        
        <div id="deepl-status" class="space-y-3">
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 bg-yellow-400 rounded-full animate-pulse"></div>
                <span class="text-sm text-gray-600">Vérification en cours...</span>
            </div>
        </div>
    </div>

    <!-- Test de traduction en temps réel -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-semibold mb-4">Test de Traduction en Temps Réel</h2>
        
        <div class="space-y-4">
            <div>
                <label for="test-text" class="block text-sm font-medium text-gray-700 mb-2">
                    Texte français à traduire:
                </label>
                <textarea id="test-text" 
                         rows="3" 
                         class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                         placeholder="Entrez votre texte en français...">Villa luxueuse avec piscine privée située dans le quartier huppé de Cocody</textarea>
            </div>
            
            <button id="translate-btn" 
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-language mr-2"></i>
                Traduire avec DeepL
            </button>
            
            <div id="translation-result" class="hidden">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Traduction anglaise:
                </label>
                <div class="bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 min-h-[80px]">
                    <div id="translated-text" class="text-gray-700"></div>
                </div>
                <div id="translation-time" class="text-xs text-gray-500 mt-1"></div>
            </div>
        </div>
    </div>

    <!-- Test des traductions de résidences -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-semibold mb-4">État des Traductions des Résidences</h2>
        
        <div id="residences-translation-status" class="space-y-3">
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 bg-yellow-400 rounded-full animate-pulse"></div>
                <span class="text-sm text-gray-600">Chargement des données...</span>
            </div>
        </div>
        
        <div class="mt-4 space-x-2">
            <button id="check-residences-btn" 
                    class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                <i class="fas fa-sync mr-2"></i>
                Vérifier les traductions
            </button>
            
            <button id="translate-all-residences-btn" 
                    class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors">
                <i class="fas fa-language mr-2"></i>
                Traduire toutes les résidences
            </button>
        </div>
    </div>

    <!-- Observer Test -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-semibold mb-4">Test de l'Observer Auto-Translation</h2>
        
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
            <h3 class="font-medium text-blue-800 mb-2">À propos de ce test</h3>
            <p class="text-sm text-blue-700">
                Ce test simule la création d'une nouvelle résidence pour vérifier que l'observer 
                traduit automatiquement les champs français vers l'anglais.
            </p>
        </div>
        
        <button id="test-observer-btn" 
                class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors">
            <i class="fas fa-flask mr-2"></i>
            Tester l'Observer
        </button>
        
        <div id="observer-result" class="hidden mt-4">
            <div class="space-y-2 text-sm">
                <div id="observer-status" class="font-medium"></div>
                <div id="observer-details" class="bg-gray-50 border border-gray-200 rounded p-3"></div>
            </div>
        </div>
    </div>

    <!-- Langue de l'interface -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-semibold mb-4">Test du Système de Localisation</h2>
        
        <div class="space-y-4">
            <div>
                <span class="text-sm text-gray-600">Langue actuelle: </span>
                <span class="font-medium">{{ app()->getLocale() }} ({{ app()->getLocale() === 'fr' ? 'Français' : 'English' }})</span>
            </div>
            
            <div class="flex gap-2">
                <a href="{{ request()->fullUrlWithQuery(['lang' => 'fr']) }}" 
                   class="px-4 py-2 rounded-lg text-sm {{ app()->getLocale() === 'fr' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                    🇫🇷 Français
                </a>
                <a href="{{ request()->fullUrlWithQuery(['lang' => 'en']) }}" 
                   class="px-4 py-2 rounded-lg text-sm {{ app()->getLocale() === 'en' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                    🇬🇧 English
                </a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Check DeepL Status
    checkDeepLStatus();
    
    // Translation test
    document.getElementById('translate-btn').addEventListener('click', testTranslation);
    
    // Observer test
    document.getElementById('test-observer-btn').addEventListener('click', testObserver);
    
    // Check residences translations
    document.getElementById('check-residences-btn').addEventListener('click', checkResidencesTranslations);
    
    // Translate all residences
    document.getElementById('translate-all-residences-btn').addEventListener('click', translateAllResidences);
    
    function checkDeepLStatus() {
        fetch('/api/translation/status')
            .then(response => response.json())
            .then(data => {
                const statusDiv = document.getElementById('deepl-status');
                if (data.available) {
                    statusDiv.innerHTML = `
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            <span class="text-sm text-green-700 font-medium">DeepL API disponible</span>
                        </div>
                        <div class="text-xs text-gray-600 mt-1">
                            Utilisation: ${data.usage.characters_used}/${data.usage.characters_limit} caractères (${data.usage.percentage_used}%)
                        </div>
                    `;
                } else {
                    statusDiv.innerHTML = `
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                            <span class="text-sm text-red-700 font-medium">DeepL API non disponible</span>
                        </div>
                        <div class="text-xs text-gray-600 mt-1">Mode de traduction de fallback actif</div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error checking DeepL status:', error);
            });
    }
    
    function testTranslation() {
        const text = document.getElementById('test-text').value;
        const button = document.getElementById('translate-btn');
        const resultDiv = document.getElementById('translation-result');
        
        if (!text.trim()) {
            alert('Veuillez entrer un texte à traduire');
            return;
        }
        
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Traduction...';
        
        const startTime = Date.now();
        
        fetch('/api/translation/translate', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ text: text })
        })
        .then(response => response.json())
        .then(data => {
            const endTime = Date.now();
            const duration = endTime - startTime;
            
            document.getElementById('translated-text').textContent = data.translation;
            document.getElementById('translation-time').textContent = `Traduit en ${duration}ms${data.provider ? ' via ' + data.provider : ''}`;
            
            resultDiv.classList.remove('hidden');
        })
        .catch(error => {
            console.error('Translation error:', error);
            alert('Erreur lors de la traduction');
        })
        .finally(() => {
            button.disabled = false;
            button.innerHTML = '<i class="fas fa-language mr-2"></i>Traduire avec DeepL';
        });
    }
    
    function testObserver() {
        const button = document.getElementById('test-observer-btn');
        const resultDiv = document.getElementById('observer-result');
        
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Test en cours...';
        
        fetch('/api/translation/test-observer', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            const statusDiv = document.getElementById('observer-status');
            const detailsDiv = document.getElementById('observer-details');
            
            if (data.success) {
                statusDiv.innerHTML = '<span class="text-green-700">✅ Observer fonctionne correctement</span>';
                detailsDiv.innerHTML = `
                    <div><strong>Résidence créée:</strong> ID ${data.residence.id}</div>
                    <div><strong>Nom FR:</strong> ${data.residence.name}</div>
                    <div><strong>Nom EN:</strong> ${data.residence.name_en || 'Non traduit'}</div>
                    <div><strong>Description EN:</strong> ${data.residence.description_en ? data.residence.description_en.substring(0, 100) + '...' : 'Non traduite'}</div>
                `;
            } else {
                statusDiv.innerHTML = '<span class="text-red-700">❌ Erreur de l\'observer</span>';
                detailsDiv.innerHTML = `<div class="text-red-600">${data.error}</div>`;
            }
            
            resultDiv.classList.remove('hidden');
        })
        .catch(error => {
            console.error('Observer test error:', error);
        })
        .finally(() => {
            button.disabled = false;
            button.innerHTML = '<i class="fas fa-flask mr-2"></i>Tester l\'Observer';
        });
    }
    
    function checkResidencesTranslations() {
        // Implementation for checking residences translations
        console.log('Checking residences translations...');
    }
    
    function translateAllResidences() {
        // Implementation for translating all residences
        console.log('Translating all residences...');
    }
});
</script>
@endsection
