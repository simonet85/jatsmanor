# 🌐 Guide DeepL Translation Service

## Configuration

### 1. Obtenir une clé API DeepL

1. Allez sur https://www.deepl.com/pro-api
2. Créez un compte (gratuit jusqu'à 500 000 caractères/mois)
3. Obtenez votre clé API

### 2. Configuration Laravel

Ajoutez dans votre fichier `.env` :

```bash
DEEPL_API_KEY=your-deepl-api-key-here
```

## Utilisation

### Commandes disponibles

```bash
# Tester DeepL
php artisan deepl:test
php artisan deepl:test "Votre texte à traduire"

# Traduire toutes les résidences
php artisan residences:translate

# Forcer la re-traduction
php artisan residences:translate --force
```

### Utilisation programmatique

```php
use App\Services\ProfessionalTranslationService;

$translator = app(ProfessionalTranslationService::class);

// Traduction simple
$translation = $translator->translateToEnglish('Villa luxueuse à Cocody');

// Vérifier si DeepL est disponible
if ($translator->isDeepLAvailable()) {
    echo "DeepL is configured and working";
}

// Obtenir l'usage
$usage = $translator->getUsageInfo();
echo "Used: {$usage['characters_used']}/{$usage['characters_limit']}";
```

## Fonctionnalités

### ✅ Avantages de DeepL

-   Traductions de qualité professionnelle
-   Compréhension du contexte
-   Respect des nuances linguistiques
-   API rapide et fiable

### 🔄 Système de fallback

-   Si DeepL n'est pas configuré → utilise le dictionnaire local
-   Si DeepL échoue → fallback automatique
-   Pas d'interruption de service

### 💾 Cache intelligent

-   Traductions mises en cache 24h
-   Évite les appels API répétés
-   Économise le quota DeepL

### 📊 Monitoring

-   Suivi de l'usage DeepL
-   Logs détaillés
-   Alertes en cas d'erreur

## Tarification DeepL

### Gratuit

-   500 000 caractères/mois
-   Parfait pour débuter

### Pro

-   À partir de 5,99€/mois
-   Millions de caractères
-   Support prioritaire

## Bonnes pratiques

1. **Testez d'abord** : `php artisan deepl:test`
2. **Surveillez l'usage** : Vérifiez régulièrement votre quota
3. **Cache activé** : Laissez le cache activé en production
4. **Fallback ready** : Le système fonctionne sans DeepL

## Alternatives

Si DeepL ne convient pas :

1. **Google Translate API** : `composer require google/cloud-translate`
2. **Azure Translator** : `composer require microsoft/cognitive-services-translator`
3. **AWS Translate** : Via SDK AWS
4. **Dictionnaire local** : Système actuel étendu
