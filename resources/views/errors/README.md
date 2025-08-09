# Pages d'Erreur Personnalisées - Jatsmanor

Ce dossier contient les pages d'erreur personnalisées pour la plateforme Jatsmanor.

## Pages Disponibles

### 404.blade.php - Page Non Trouvée

-   **Code d'erreur** : 404
-   **Couleur** : Bleu
-   **Fonctionnalités** :
    -   Suggestions de navigation avec liens vers les sections principales
    -   Boutons d'action (Retour/Accueil)
    -   Animations de fond flottantes
    -   Design responsive
    -   Multilingue (FR/EN)

### 403.blade.php - Accès Interdit

-   **Code d'erreur** : 403
-   **Couleur** : Jaune/Orange
-   **Fonctionnalités** :
    -   Explication des raisons du blocage
    -   Bouton de connexion pour les invités
    -   Icônes de sécurité en arrière-plan
    -   Conseils contextuels selon le statut de l'utilisateur

### 500.blade.php - Erreur Interne

-   **Code d'erreur** : 500
-   **Couleur** : Rouge/Orange
-   **Fonctionnalités** :
    -   Explications techniques simples
    -   Bouton "Réessayer"
    -   Conseils de dépannage
    -   Lien pour signaler le problème

### generic.blade.php - Erreur Générique

-   **Usage** : Toutes les autres erreurs HTTP
-   **Couleur** : Gris/Slate
-   **Fonctionnalités** :
    -   Affichage du code d'erreur dynamique
    -   Message d'erreur personnalisé
    -   Détails techniques en mode debug
    -   Actions génériques (Réessayer/Accueil/Contact)

### layout.blade.php - Layout Réutilisable

-   **Usage** : Template de base pour créer de nouvelles pages d'erreur
-   **Fonctionnalités** :
    -   Variables configurables (couleurs, icônes, messages)
    -   Structure cohérente
    -   Facilite l'ajout de nouvelles pages d'erreur

## Configuration Laravel

Laravel utilise automatiquement les vues dans `resources/views/errors/` selon le code d'erreur HTTP :

-   `404.blade.php` → Erreur 404
-   `403.blade.php` → Erreur 403
-   `500.blade.php` → Erreur 500
-   `generic.blade.php` → Autres erreurs (si configuré)

## Traductions

Toutes les pages d'erreur utilisent le système de traduction Laravel :

-   **Français** : `resources/lang/fr/messages.php` → `'errors'`
-   **Anglais** : `resources/lang/en/messages.php` → `'errors'`

### Structure des traductions :

```php
'errors' => [
    '404' => [
        'title' => 'Page non trouvée',
        'heading' => 'Oups ! Page introuvable',
        'message' => '...',
        'suggestions' => [...],
        // ...
    ],
    // ...
]
```

## Routes de Test (Développement)

Pour tester les pages d'erreur en local :

-   `/test-404` → Déclenche une erreur 404
-   `/test-403` → Déclenche une erreur 403
-   `/test-500` → Déclenche une erreur 500

**Note** : Ces routes ne sont disponibles qu'en environnement `local`.

## Personnalisation

### Ajouter une nouvelle page d'erreur :

1. **Créer la vue** : `resources/views/errors/{code}.blade.php`
2. **Ajouter les traductions** dans `messages.php`
3. **Suivre la structure** des pages existantes

### Exemple pour une erreur 429 (Too Many Requests) :

```php
// resources/views/errors/429.blade.php
@extends('layouts.app')
@section('title', trans('messages.errors.429.title') . ' - Jatsmanor')
@section('content')
<!-- Contenu similaire aux autres pages -->
@endsection
```

### Couleurs par type d'erreur :

-   **4xx (Client)** : Bleu, Jaune, Orange
-   **5xx (Serveur)** : Rouge, Orange
-   **Générique** : Gris, Slate

## Structure CSS

Toutes les pages utilisent **Tailwind CSS** avec :

-   Gradients de fond cohérents
-   Animations CSS natives
-   Design responsive
-   Accessibilité (focus, contraste)

## Bonnes Pratiques

1. **UX** : Toujours proposer des actions utiles (Retour, Accueil, Contact)
2. **SEO** : Titre de page descriptif avec le code d'erreur
3. **Accessibilité** : Icônes avec signification, contraste suffisant
4. **Performance** : Utiliser les animations CSS plutôt que JavaScript
5. **Sécurité** : Ne pas exposer d'informations sensibles (sauf en debug)

## Maintenance

-   **Tester régulièrement** les pages d'erreur
-   **Mettre à jour les traductions** lors d'ajouts de fonctionnalités
-   **Vérifier les liens** vers les pages principales
-   **Surveiller les logs** pour identifier les erreurs fréquentes
