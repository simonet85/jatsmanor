# Guide d'Utilisation - Système de Localisation Multilingue

**Jatsmanor Residences - Version 1.0**

---

## 📋 **Vue d'ensemble**

Ce système permet à votre site web de fonctionner en **français** et **anglais** avec traduction automatique professionnelle des contenus immobiliers.

---

## 🌍 **Fonctionnalités principales**

### 1. **Changement de langue**

-   **Drapeaux dans la navigation** : Cliquez sur 🇫🇷 ou 🇬🇧 pour changer la langue
-   **Mémorisation** : Le choix de langue est sauvegardé pour la session
-   **URL adaptées** : Les liens s'adaptent automatiquement à la langue

### 2. **Traduction automatique des résidences**

-   **Nouveau contenu** : Traduit automatiquement lors de la création
-   **Modification** : Met à jour les traductions lors des modifications
-   **Qualité professionnelle** : Utilise l'API DeepL pour des traductions précises

---

## 🏠 **Gestion des Résidences**

### **Création d'une nouvelle résidence**

1. Saisissez le contenu en **français** (langue principale)
2. Le système traduit automatiquement en anglais :
    - Nom de la résidence
    - Description complète
    - Description courte
3. Les traductions apparaissent instantanément

### **Modification d'une résidence**

1. Modifiez le contenu français
2. La traduction anglaise se met à jour automatiquement
3. Aucune action manuelle requise

---

## ⚙️ **Administration**

### **Commandes disponibles**

#### **Traduire toutes les résidences**

```bash
php artisan residences:translate --force
```

-   Traduit toutes les résidences existantes
-   Utilise `--force` pour retraduire même si déjà traduit
-   Affiche le nombre de résidences traitées

#### **Tester le système DeepL**

```bash
php artisan deepl:test
```

-   Vérifie la connexion à l'API DeepL
-   Permet de tester des traductions personnalisées
-   Affiche l'usage des caractères

#### **Diagnostiquer les problèmes**

```bash
php artisan deepl:debug
```

-   Vérifie la configuration
-   Teste la connectivité
-   Identifie les problèmes SSL

---

## 📊 **Surveillance et monitoring**

### **Usage DeepL**

-   **Limite gratuite** : 500,000 caractères/mois
-   **Monitoring automatique** : Affichage de l'usage dans les commandes
-   **Logs** : Toutes les traductions sont enregistrées

### **Système de fallback**

Si DeepL n'est pas disponible :

-   Le système utilise un dictionnaire de traductions de base
-   Aucune interruption de service
-   Les traductions peuvent être améliorées plus tard

---

## 🔧 **Configuration**

### **Variables d'environnement (.env)**

```env
# API DeepL (obligatoire pour traductions professionnelles)
DEEPL_API_KEY=votre-clé-api-deepl

# Fix SSL pour développement local (optionnel)
CURL_SSL_VERIFY=false
```

### **Obtenir une clé DeepL**

1. Allez sur : https://www.deepl.com/pro-api
2. Créez un compte gratuit (500k caractères/mois)
3. Copiez votre clé API dans le fichier `.env`

---

## 🌐 **Interface utilisateur**

### **Navigation multilingue**

-   **Drapeaux cliquables** dans le menu principal
-   **Indication visuelle** de la langue active
-   **Transitions fluides** entre les langues

### **Contenu adaptatif**

-   **Textes statiques** : Traduits via les fichiers de langue Laravel
-   **Contenu dynamique** : Traduit automatiquement par DeepL
-   **Formatage préservé** : Mise en page identique dans les deux langues

---

## ❓ **Résolution de problèmes**

### **La traduction ne fonctionne pas**

1. Vérifiez la clé DeepL dans `.env`
2. Testez avec : `php artisan deepl:debug`
3. Vérifiez les logs Laravel

### **Problèmes SSL (développement local)**

1. Ajoutez `CURL_SSL_VERIFY=false` dans `.env`
2. Ou configurez le certificat CA bundle
3. Le système utilise le fallback automatiquement

### **Traductions incomplètes**

1. Utilisez `php artisan residences:translate --force`
2. Vérifiez l'usage DeepL (limite atteinte ?)
3. Le fallback prend le relais automatiquement

---

## 📈 **Bonnes pratiques**

### **Création de contenu**

-   **Rédigez en français** (langue principale)
-   **Soyez précis** dans les descriptions
-   **Utilisez un vocabulaire immobilier** standard

### **Maintenance**

-   **Surveillez l'usage DeepL** mensuellement
-   **Sauvegardez régulièrement** la base de données
-   **Testez périodiquement** avec `php artisan deepl:test`

### **Performance**

-   Les traductions sont **mises en cache** 24h
-   **Pas de surcharge** sur le site public
-   **Traduction à la demande** uniquement

---

## 🎯 **Support**

Pour toute question ou problème :

1. Consultez les logs Laravel : `storage/logs/laravel.log`
2. Testez avec les commandes de diagnostic
3. Vérifiez la documentation technique pour plus de détails

---

_Documentation générée pour Jatsmanor Residences - Système de localisation v1.0_
