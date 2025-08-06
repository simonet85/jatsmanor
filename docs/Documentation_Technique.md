# Documentation Technique - Système de Localisation Multilingue

**Jatsmanor Residences - Architecture & Implémentation**

---

## 🏗️ **Architecture générale**

### **Vue d'ensemble du système**

```
┌─────────────────┐    ┌──────────────────┐    ┌─────────────────┐
│   Frontend      │    │   Laravel Core   │    │   DeepL API     │
│   (Blade Views) │◄──►│   (Middleware)   │◄──►│   (Translation) │
└─────────────────┘    └──────────────────┘    └─────────────────┘
         │                        │                        │
         ▼                        ▼                        ▼
┌─────────────────┐    ┌──────────────────┐    ┌─────────────────┐
│   Fichiers      │    │   Database       │    │   Cache         │
│   Lang (FR/EN)  │    │   (Residences)   │    │   (24h TTL)     │
└─────────────────┘    └──────────────────┘    └─────────────────┘
```

---

## 🔧 **Composants techniques**

### **1. Middleware de localisation**

**Fichier** : `app/Http/Middleware/LocalizationMiddleware.php`

```php
class LocalizationMiddleware
{
    public function handle($request, Closure $next)
    {
        // Détection automatique de la langue
        $locale = $request->session()->get('locale', config('app.locale'));
        App::setLocale($locale);
        return $next($request);
    }
}
```

**Fonctionnalités** :

-   Détection automatique de la langue préférée
-   Gestion des sessions utilisateur
-   Fallback vers la langue par défaut

### **2. Contrôleur de langue**

**Fichier** : `app/Http/Controllers/LanguageController.php`

```php
class LanguageController extends Controller
{
    public function switchLang($lang)
    {
        if (in_array($lang, ['en', 'fr'])) {
            Session::put('locale', $lang);
        }
        return redirect()->back();
    }
}
```

### **3. Service de traduction professionnel**

**Fichier** : `app/Services/ProfessionalTranslationService.php`

#### **Architecture du service**

```php
class ProfessionalTranslationService
{
    private ?Translator $translator = null;

    // Initialisation avec gestion d'erreurs
    public function __construct()

    // Traduction principale avec cache
    public function translateToEnglish(string $text, bool $useCache = true): string

    // Traduction par lot pour performance
    public function translateBatch(array $texts): array

    // Vérification disponibilité API
    public function isDeepLAvailable(): bool

    // Post-traitement spécialisé immobilier
    private function postProcessTranslation(string $translation): string

    // Fallback intelligent
    private function fallbackTranslation(string $text): string
}
```

#### **Gestion du cache**

```php
$cacheKey = 'translation:deepl:' . md5($text);
Cache::put($cacheKey, $translation, now()->addDay());
```

-   **TTL** : 24 heures
-   **Clé unique** : Hash MD5 du texte source
-   **Invalidation** : Automatique après expiration

#### **Post-traitement spécialisé**

```php
private function postProcessTranslation(string $translation): string
{
    $corrections = [
        // Prépositions
        ' à ' => ' in ',
        ' avec ' => ' with ',

        // Quartiers d'Abidjan
        'à Abobo' => 'in Abobo',
        'à Cocody' => 'in Cocody',

        // Termes immobiliers
        'haut standing' => 'high-end',
        'tout équipé' => 'fully equipped',
    ];

    foreach ($corrections as $french => $english) {
        $translation = str_ireplace($french, $english, $translation);
    }

    return ucfirst(trim($translation));
}
```

---

## 🗄️ **Base de données**

### **Schema de la table `residences`**

```sql
ALTER TABLE residences
ADD COLUMN name_en VARCHAR(255) NULL AFTER name,
ADD COLUMN description_en TEXT NULL AFTER description,
ADD COLUMN short_description_en TEXT NULL AFTER short_description;

CREATE INDEX idx_residences_name_en ON residences(name_en);
CREATE INDEX idx_residences_translations ON residences(name_en, description_en);
```

### **Modèle Eloquent**

```php
class Residence extends Model
{
    protected $fillable = [
        'name', 'name_en',
        'description', 'description_en',
        'short_description', 'short_description_en'
    ];

    // Accesseurs pour traductions
    public function getNameAttribute($value)
    {
        return app()->getLocale() === 'en' && $this->name_en
            ? $this->name_en
            : $value;
    }
}
```

---

## 🔄 **Observer Pattern**

### **Observer des résidences**

**Fichier** : `app/Observers/ResidenceObserver.php`

```php
class ResidenceObserver
{
    public function __construct(
        private ProfessionalTranslationService $translationService
    ) {}

    public function creating(Residence $residence): void
    {
        $this->autoTranslate($residence);
    }

    public function updating(Residence $residence): void
    {
        if ($residence->isDirty(['name', 'description', 'short_description'])) {
            $this->autoTranslate($residence);
        }
    }

    private function autoTranslate(Residence $residence): void
    {
        if ($residence->name && !$residence->name_en) {
            $residence->name_en = $this->translationService
                ->translateToEnglish($residence->name);
        }
        // ... autres champs
    }
}
```

**Enregistrement** dans `AppServiceProvider` :

```php
public function boot(): void
{
    Residence::observe(ResidenceObserver::class);
}
```

---

## 🛠️ **Commandes Artisan**

### **1. Traduction en batch**

**Fichier** : `app/Console/Commands/TranslateResidences.php`

```php
class TranslateResidences extends Command
{
    protected $signature = 'residences:translate {--force : Force retranslation}';

    public function handle()
    {
        $residences = Residence::query()
            ->when(!$this->option('force'), function($query) {
                return $query->whereNull('name_en');
            })
            ->get();

        $bar = $this->output->createProgressBar($residences->count());

        foreach ($residences as $residence) {
            $this->translateResidence($residence);
            $bar->advance();
        }

        $bar->finish();
    }
}
```

### **2. Test et diagnostic**

**Fichier** : `app/Console/Commands/TestDeepLTranslation.php`

```php
class TestDeepLTranslation extends Command
{
    protected $signature = 'deepl:test';

    public function handle()
    {
        $service = new ProfessionalTranslationService();

        if ($service->isDeepLAvailable()) {
            $this->info('✅ DeepL API is available');
            $usage = $service->getUsageInfo();
            $this->info("Usage: {$usage['characters_used']}/{$usage['characters_limit']}");
        } else {
            $this->warn('⚠️  DeepL not available - using fallback');
        }
    }
}
```

---

## 🌐 **Intégration Frontend**

### **Middleware d'enregistrement**

**Fichier** : `app/Http/Kernel.php`

```php
protected $middlewareGroups = [
    'web' => [
        // ... autres middleware
        \App\Http\Middleware\LocalizationMiddleware::class,
    ],
];
```

### **Routes de langue**

**Fichier** : `routes/web.php`

```php
Route::get('/lang/{lang}', [LanguageController::class, 'switchLang'])
    ->name('lang.switch')
    ->where('lang', '[a-zA-Z]{2}');
```

### **Blade Templates**

```blade
{{-- Sélecteur de langue --}}
<div class="language-switcher">
    <a href="{{ route('lang.switch', 'fr') }}"
       class="{{ app()->getLocale() == 'fr' ? 'active' : '' }}">
        🇫🇷 Français
    </a>
    <a href="{{ route('lang.switch', 'en') }}"
       class="{{ app()->getLocale() == 'en' ? 'active' : '' }}">
        🇬🇧 English
    </a>
</div>

{{-- Affichage adaptatif du contenu --}}
<h1>{{ $residence->name }}</h1>
<p>{{ $residence->description }}</p>
```

---

## 📦 **Dépendances externes**

### **Package DeepL**

```json
{
    "require": {
        "deeplcom/deepl-php": "^1.12.0"
    }
}
```

### **Configuration services**

**Fichier** : `config/services.php`

```php
'deepl' => [
    'api_key' => env('DEEPL_API_KEY'),
],
```

---

## 🔒 **Sécurité et gestion d'erreurs**

### **Validation des entrées**

```php
// Dans ResidenceObserver
private function autoTranslate(Residence $residence): void
{
    try {
        if ($residence->name && !$residence->name_en) {
            $translated = $this->translationService->translateToEnglish($residence->name);
            $residence->name_en = Str::limit($translated, 250); // Limite base de données
        }
    } catch (\Exception $e) {
        Log::error('Translation failed for residence: ' . $e->getMessage());
        // Continue sans bloquer la sauvegarde
    }
}
```

### **Gestion SSL en développement**

```php
// Dans .env pour développement local
CURL_SSL_VERIFY=false

// Ou configuration CA bundle permanente
curl.cainfo = "C:\laragon\etc\ssl\cacert.pem"
openssl.cafile = "C:\laragon\etc\ssl\cacert.pem"
```

---

## 📊 **Performance et optimisation**

### **Cache strategy**

-   **Traductions** : Cache Redis/File 24h
-   **Sessions langue** : Cache session Laravel
-   **Requêtes DB** : Index sur colonnes `*_en`

### **Lazy loading**

```php
// Chargement conditionnel des traductions
public function getNameAttribute($value)
{
    if (app()->getLocale() === 'en' && $this->name_en) {
        return $this->name_en;
    }
    return $value;
}
```

### **Batch processing**

```php
// Traitement par lots pour éviter les timeouts
public function translateBatch(array $texts): array
{
    $chunks = array_chunk($texts, 10); // Lots de 10
    $results = [];

    foreach ($chunks as $chunk) {
        $batchResults = $this->translator->translateTextBulk($chunk, 'fr', 'en-US');
        $results = array_merge($results, $batchResults);
    }

    return $results;
}
```

---

## 🧪 **Tests**

### **Tests unitaires suggérés**

```php
class TranslationServiceTest extends TestCase
{
    public function test_translates_simple_text()
    {
        $service = new ProfessionalTranslationService();
        $result = $service->translateToEnglish('Bonjour');
        $this->assertEquals('Hello', $result);
    }

    public function test_fallback_when_deepl_unavailable()
    {
        // Mock DeepL failure
        $result = $service->translateToEnglish('Villa luxueuse');
        $this->assertStringContains('luxury', strtolower($result));
    }

    public function test_post_processing_abidjan_districts()
    {
        $result = $service->translateToEnglish('Appartement à Cocody');
        $this->assertStringContains('in Cocody', $result);
    }
}
```

---

## 📝 **Logs et monitoring**

### **Structure des logs**

```php
// Logs de traduction
Log::info("DeepL Translation: '{$text}' -> '{$translation}'");

// Logs d'erreur
Log::error('Failed to initialize DeepL Translator: ' . $e->getMessage());

// Logs de performance
Log::debug("Translation cached for 24h: {$cacheKey}");
```

### **Monitoring usage DeepL**

```php
public function getUsageInfo(): ?array
{
    try {
        $usage = $this->translator->getUsage();
        return [
            'characters_used' => $usage->character->count,
            'characters_limit' => $usage->character->limit,
            'percentage_used' => round(($usage->character->count / $usage->character->limit) * 100, 2),
        ];
    } catch (\Exception $e) {
        return null;
    }
}
```

---

_Documentation technique v1.0 - Jatsmanor Residences Localization System_
