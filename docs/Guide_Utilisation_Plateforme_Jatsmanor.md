# 📚 Guide d'Utilisation - Plateforme Jatsmanor

## Table des Matières

1. [Vue d'ensemble](#vue-densemble)
2. [Guide Administrateur](#guide-administrateur)
3. [Guide Client](#guide-client)
4. [Fonctionnalités Communes](#fonctionnalités-communes)
5. [FAQ](#faq)
6. [Support](#support)

---

## Vue d'ensemble

**Jatsmanor** est une plateforme de réservation de résidences meublées à Abidjan, offrant une solution complète pour la gestion des réservations, des résidences et des clients.

### Caractéristiques principales

-   🏠 Gestion complète des résidences
-   📅 Système de réservation en ligne
-   💳 Paiements sécurisés
-   🌍 Interface multilingue (Français/Anglais)
-   📱 Design responsive
-   🔐 Système de rôles et permissions

---

## Guide Administrateur

### 🔐 Accès Administrateur

#### Connexion

1. Accédez à `https://votre-domaine.com/login`
2. Saisissez vos identifiants administrateur
3. Cliquez sur "Se connecter"

#### Tableau de Bord

Le tableau de bord administrateur (`/dashboard`) affiche :

-   📊 Statistiques en temps réel
-   📈 Graphiques de performance
-   🎯 Indicateurs clés (KPI)
-   🔔 Notifications importantes

### 🏠 Gestion des Résidences

#### Créer une Résidence

1. **Navigation** : Dashboard → "Gérer les résidences"
2. **Nouveau** : Cliquez sur "Ajouter une résidence"
3. **Informations de base** :
    - Nom de la résidence
    - Description complète
    - Description courte
    - Localisation
4. **Détails** :
    - Prix par nuit (FCFA)
    - Superficie (m²)
    - Nombre d'invités maximum
    - Étage
5. **Équipements** : Sélectionnez les équipements disponibles
6. **Image** : Téléchargez une image principale
7. **Options** :
    - ✅ Résidence active
    - ⭐ Résidence mise en avant
8. **Sauvegarde** : Cliquez sur "Créer la résidence"

#### Modifier une Résidence

1. Accédez à la liste des résidences
2. Cliquez sur l'icône "✏️" de modification
3. Modifiez les informations nécessaires
4. Sauvegardez les modifications

#### Supprimer une Résidence

1. Cliquez sur l'icône "🗑️" de suppression
2. Confirmez la suppression
3. ⚠️ **Attention** : Cette action est irréversible

### 🛠️ Gestion des Équipements

#### Accès

Dashboard → "Gérer les équipements"

#### Ajouter un Équipement

1. Cliquez sur "Ajouter un équipement"
2. **Informations** :
    - Nom de l'équipement
    - Icône FontAwesome (ex: `wifi`, `car`, `swimming-pool`)
    - Description
3. Sauvegardez

#### Modifier/Supprimer

-   **Modifier** : Cliquez sur l'icône "✏️"
-   **Supprimer** : Cliquez sur l'icône "🗑️"

### 📅 Gestion des Réservations

#### Vue d'ensemble

Dashboard → "Gérer les réservations"

#### Statuts des Réservations

-   🟡 **En attente** : Nouvelle réservation
-   🟢 **Confirmée** : Réservation acceptée
-   🔵 **En cours** : Client actuellement hébergé
-   ✅ **Terminée** : Séjour terminé
-   ❌ **Annulée** : Réservation annulée

#### Actions sur les Réservations

1. **Voir les détails** : Cliquez sur une réservation
2. **Confirmer** : Changez le statut à "Confirmée"
3. **Annuler** : Changez le statut à "Annulée"
4. **Exporter** : Téléchargez les données

### 👥 Gestion des Utilisateurs

#### Accès

Dashboard → "Gérer les utilisateurs"

#### Rôles Disponibles

-   **👑 Administrateur** : Accès complet
-   **👤 Client** : Réservations uniquement

#### Actions

-   **Voir profil** : Détails complets
-   **Modifier rôle** : Changer les permissions
-   **Désactiver** : Suspendre l'accès

### 💬 Gestion des Messages

#### Messages de Contact

Dashboard → "Messages"

#### Traitement des Messages

1. **Consulter** : Cliquez sur un message
2. **Marquer lu** : Change le statut
3. **Répondre** : Par email externe
4. **Archiver** : Déplace vers les archives

### ⚙️ Paramètres du Site

#### Accès

Dashboard → "Paramètres"

#### Sections Disponibles

##### 📋 Informations Générales

-   Nom du site
-   Description du site

##### 📞 Informations de Contact

-   Email de contact
-   Téléphone
-   Adresse

##### 📅 Paramètres de Réservation

-   Durée minimum/maximum de séjour
-   Délai d'annulation
-   Frais de nettoyage
-   Caution
-   Confirmation automatique

##### 🌍 Paramètres de Langue

-   **Sélecteur de langue** : Activer/désactiver
-   **Langue par défaut** : Français ou Anglais

##### 🎨 Paramètres Frontend

-   Titre principal
-   Sous-titre
-   Image hero
-   Description footer

---

## Guide Client

### 🔐 Inscription et Connexion

#### Créer un Compte

1. Accédez à `https://votre-domaine.com/register`
2. **Informations requises** :
    - Nom complet
    - Adresse email
    - Mot de passe (minimum 8 caractères)
    - Confirmation du mot de passe
3. **Conditions** : Acceptez les conditions d'utilisation
4. **Newsletter** : Optionnel
5. Cliquez sur "S'inscrire"

#### Connexion

1. Accédez à `https://votre-domaine.com/login`
2. Saisissez email et mot de passe
3. ☑️ "Se souvenir de moi" (optionnel)
4. Cliquez sur "Se connecter"

### 🏠 Recherche et Réservation

#### Rechercher des Résidences

1. **Page d'accueil** : Utilisez le formulaire de recherche
2. **Critères** :
    - Dates d'arrivée et départ
    - Nombre d'invités
3. **Résultats** : Parcourez les résidences disponibles

#### Filtres Avancés

-   **Type de chambre** : Single, Double, Suite
-   **Prix maximum**
-   **Équipements** : Wi-Fi, Climatisation, Parking, etc.

#### Réserver une Résidence

1. **Sélection** : Cliquez sur une résidence
2. **Détails** : Consultez les informations complètes
3. **Dates** : Vérifiez la disponibilité
4. **Réservation** : Cliquez sur "Réserver maintenant"

#### Processus de Réservation

##### Étape 1 : Informations Client

-   Nom complet
-   Email
-   Téléphone
-   Adresse

##### Étape 2 : Détails du Séjour

-   Dates confirmées
-   Nombre d'invités
-   Demandes spéciales (optionnel)

##### Étape 3 : Paiement

-   **Méthodes acceptées** :
    -   💳 Cartes bancaires
    -   🏦 Virement bancaire
    -   📱 Orange Money
    -   📱 MTN Mobile Money
-   **Récapitulatif** :
    -   Prix par nuit
    -   Nombre de nuits
    -   Frais de nettoyage
    -   Caution
    -   **Total**

##### Étape 4 : Confirmation

-   Email de confirmation automatique
-   Numéro de réservation
-   Instructions de check-in

### 👤 Gestion du Profil

#### Accès

Connectez-vous → Menu utilisateur → "Mon Profil"

#### Sections Disponibles

##### 📋 Informations Personnelles

-   Nom complet
-   Email
-   Téléphone
-   Adresse

##### 🔒 Sécurité

-   Changer le mot de passe
-   Historique des connexions

##### 🖼️ Avatar

-   Télécharger une photo de profil

### 📅 Mes Réservations

#### Accès

Menu utilisateur → "Mes Réservations"

#### Informations Affichées

-   **Résidence** : Nom et image
-   **Dates** : Arrivée et départ
-   **Statut** : En attente, Confirmée, etc.
-   **Prix total**
-   **Actions** : Voir détails, Annuler

#### Annulation

1. Cliquez sur "Annuler la réservation"
2. **Conditions** :
    - Gratuite jusqu'à 48h avant l'arrivée
    - Frais d'annulation après ce délai
3. Confirmez l'annulation

---

## Fonctionnalités Communes

### 🌍 Changement de Langue

#### Sélecteur de Langue

-   **Position** : Navigation principale
-   **Langues** : Français 🇫🇷 / English 🇬🇧
-   **Persistance** : Le choix est mémorisé

#### Traductions Complètes

-   Interface utilisateur
-   Messages d'erreur
-   Emails automatiques
-   Contenu des résidences

### 📱 Responsive Design

#### Compatibilité

-   **Desktop** : Expérience complète
-   **Tablette** : Interface adaptée
-   **Mobile** : Navigation optimisée

#### Fonctionnalités Mobiles

-   Menu hamburger
-   Touch-friendly
-   Images optimisées
-   Formulaires adaptés

### 🔔 Notifications

#### Types de Notifications

-   **Email** : Confirmations, rappels
-   **Toast** : Messages flash
-   **Alertes** : Actions importantes

#### Configuration

-   Préférences de notification dans le profil
-   Opt-out possible pour la newsletter

---

## FAQ

### Questions Générales

**Q: Comment réinitialiser mon mot de passe ?**
R: Cliquez sur "Mot de passe oublié" sur la page de connexion et suivez les instructions.

**Q: Puis-je modifier une réservation ?**
R: Contactez l'administration pour toute modification. Les annulations sont possibles selon les conditions.

**Q: Quels modes de paiement acceptez-vous ?**
R: Cartes bancaires, virements, Orange Money, et MTN Mobile Money.

### Questions Administrateur

**Q: Comment ajouter un nouvel administrateur ?**
R: Allez dans "Gérer les utilisateurs" → Sélectionnez un utilisateur → Changez le rôle à "Administrateur".

**Q: Comment sauvegarder les données ?**
R: Les sauvegardes sont automatiques. Pour une sauvegarde manuelle, consultez la documentation technique.

**Q: Comment gérer les traductions ?**
R: Les traductions sont gérées automatiquement via DeepL API. Consultez les paramètres de langue.

### Questions Techniques

**Q: Le site est-il sécurisé ?**
R: Oui, utilisation de HTTPS, authentification sécurisée, et protection CSRF.

**Q: Puis-je personnaliser l'apparence ?**
R: Oui, via les "Paramètres Frontend" dans l'administration.

---

## Support

### 🆘 Obtenir de l'Aide

#### Canaux de Support

-   **Email** : contact@jatsmanor.ci
-   **Téléphone** : +225 07 07 07 07
-   **Heures** : Lun-Ven 8h-18h, Sam-Dim 9h-17h
-   **Urgences** : Service 24h/24

#### Informations à Fournir

-   Description détaillée du problème
-   Captures d'écran si possible
-   Navigateur et appareil utilisés
-   Étapes pour reproduire le problème

#### Temps de Réponse

-   **Email** : 24h en moyenne
-   **Urgences** : 2h maximum
-   **Questions générales** : 48h

### 🔧 Résolution de Problèmes

#### Problèmes Courants

**Problème : Page ne se charge pas**

-   Vérifiez votre connexion internet
-   Actualisez la page (Ctrl+F5)
-   Videz le cache du navigateur

**Problème : Impossible de se connecter**

-   Vérifiez vos identifiants
-   Utilisez "Mot de passe oublié"
-   Contactez le support si le problème persiste

**Problème : Erreur lors du paiement**

-   Vérifiez les informations de paiement
-   Essayez un autre mode de paiement
-   Contactez votre banque si nécessaire

### 📖 Ressources Supplémentaires

-   **Documentation technique** : Pour les développeurs
-   **Conditions d'utilisation** : Règles et politiques
-   **Politique de confidentialité** : Protection des données

---

## 📝 Notes de Version

### Version 1.0 (Actuelle)

-   ✅ Système de réservation complet
-   ✅ Gestion des résidences et équipements
-   ✅ Interface multilingue
-   ✅ Paiements multiples
-   ✅ Dashboard administrateur
-   ✅ Système de notifications

### Améliorations Prévues

-   📊 Analytics avancés
-   📱 Application mobile
-   🤖 Chatbot de support
-   🔄 API publique
-   🎨 Thèmes personnalisables

---

**© 2024 Jatsmanor - Tous droits réservés**

_Ce guide est mis à jour régulièrement. Consultez la version en ligne pour les dernières informations._
