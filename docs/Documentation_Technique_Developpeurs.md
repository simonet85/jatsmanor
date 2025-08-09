# 🛠️ Documentation Technique - Plateforme Jatsmanor

## Table des Matières

1. [Architecture Système](#architecture-système)
2. [Installation et Configuration](#installation-et-configuration)
3. [Structure du Projet](#structure-du-projet)
4. [Base de Données](#base-de-données)
5. [API et Intégrations](#api-et-intégrations)
6. [Hébergement et Déploiement](#hébergement-et-déploiement)
7. [Maintenance et Monitoring](#maintenance-et-monitoring)
8. [Sécurité](#sécurité)
9. [Performance et Optimisation](#performance-et-optimisation)
10. [Troubleshooting](#troubleshooting)

---

## Architecture Système

### 🏗️ Stack Technique

#### Backend

-   **Framework** : Laravel 10.x (PHP 8.1+)
-   **Base de données** : MySQL 8.0+
-   **Cache** : Redis (recommandé) ou File Cache
-   **Queue** : Redis/Database
-   **Session** : Database/Redis

#### Frontend

-   **CSS Framework** : Tailwind CSS 3.x
-   **JavaScript** : Vanilla JS + Alpine.js (optionnel)
-   **Build Tool** : Vite.js
-   **Icons** : FontAwesome 6.x

#### Serveur Web

-   **Recommandé** : Nginx + PHP-FPM
-   **Alternative** : Apache + mod_php
-   **SSL** : Let's Encrypt (Certbot)

### 🔄 Architecture MVC

```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│     Views       │    │   Controllers   │    │     Models      │
│   (Blade)       │◄───│   (Laravel)     │◄───│   (Eloquent)    │
│                 │    │                 │    │                 │
│ - Layouts       │    │ - HomeController│    │ - User          │
│ - Partials      │    │ - BookingCtrl   │    │ - Residence     │
│ - Components    │    │ - AdminCtrl     │    │ - Booking       │
└─────────────────┘    └─────────────────┘    └─────────────────┘
```

---

## Installation et Configuration

### 📋 Prérequis

#### Serveur

-   **OS** : Ubuntu 20.04+ / CentOS 8+ / Debian 11+
-   **RAM** : 2GB minimum, 4GB recommandé
-   **Stockage** : 20GB minimum, SSD recommandé
-   **CPU** : 2 cores minimum

#### Logiciels

```bash
# PHP 8.1+ avec extensions
php php-fpm php-mysql php-redis php-zip php-xml php-mbstring php-curl php-gd php-intl

# Serveur web
nginx # ou apache2

# Base de données
mysql-server # ou mariadb-server

# Cache (optionnel mais recommandé)
redis-server

# Outils
composer
nodejs npm
git
```

### 🚀 Installation

#### 1. Cloner le Projet

```bash
git clone https://github.com/votre-repo/jatsmanor.git
cd jatsmanor
```

#### 2. Installation des Dépendances

```bash
# PHP dependencies
composer install --optimize-autoloader --no-dev

# Node.js dependencies
npm install
npm run build
```

#### 3. Configuration Environnement

```bash
# Copier le fichier d'environnement
cp .env.example .env

# Générer la clé d'application
php artisan key:generate
```

#### 4. Configuration Base de Données

```bash
# Éditer .env
nano .env
```

```env
# Configuration de base
APP_NAME="Jatsmanor"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://votre-domaine.com

# Base de données
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=jatsmanor
DB_USERNAME=jatsmanor_user
DB_PASSWORD=mot_de_passe_securise

# Cache Redis (optionnel)
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.votre-domaine.com
MAIL_PORT=587
MAIL_USERNAME=noreply@votre-domaine.com
MAIL_PASSWORD=mot_de_passe_email
MAIL_ENCRYPTION=tls

# DeepL API (traduction)
DEEPL_API_KEY=votre_cle_deepl
DEEPL_API_URL=https://api-free.deepl.com/v2/translate

# Paiements (optionnel)
STRIPE_KEY=pk_live_...
STRIPE_SECRET=sk_live_...
```

#### 5. Migration et Seed

```bash
# Migrations
php artisan migrate --force

# Données de base
php artisan db:seed --class=SettingSeeder
php artisan db:seed --class=AmenitySeeder
php artisan db:seed --class=CreateAdminUserSeeder
```

#### 6. Optimisations Production

```bash
# Cache des configurations
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimisation Composer
composer dump-autoload --optimize

# Permissions
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

### ⚙️ Configuration Nginx

```nginx
server {
    listen 80;
    server_name votre-domaine.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name votre-domaine.com;
    root /var/www/jatsmanor/public;
    index index.php;

    # SSL Configuration
    ssl_certificate /etc/letsencrypt/live/votre-domaine.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/votre-domaine.com/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers ECDHE-RSA-AES256-GCM-SHA512:DHE-RSA-AES256-GCM-SHA512;

    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;

    # Laravel Configuration
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    # Cache static assets
    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg|woff|woff2)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }

    # Security
    location ~ /\.ht {
        deny all;
    }
}
```

---

## Structure du Projet

### 📁 Organisation des Dossiers

```
jatsmanor/
├── app/
│   ├── Console/Commands/          # Commandes Artisan personnalisées
│   ├── Http/Controllers/          # Contrôleurs
│   │   ├── Admin/                 # Administration
│   │   ├── Api/                   # API endpoints
│   │   └── Auth/                  # Authentification
│   ├── Models/                    # Modèles Eloquent
│   ├── Services/                  # Services métier
│   ├── Observers/                 # Observateurs de modèles
│   ├── Policies/                  # Politiques d'autorisation
│   └── Helpers/                   # Fonctions utilitaires
├── database/
│   ├── migrations/                # Migrations de base
│   └── seeders/                   # Données de test/production
├── resources/
│   ├── views/                     # Templates Blade
│   │   ├── layouts/               # Layouts principaux
│   │   ├── partials/              # Composants réutilisables
│   │   ├── admin/                 # Interface admin
│   │   └── auth/                  # Pages d'authentification
│   ├── lang/                      # Traductions
│   └── js/css/                    # Assets frontend
├── routes/
│   ├── web.php                    # Routes web
│   ├── api.php                    # Routes API
│   └── dashboard.php              # Routes admin
├── storage/
│   ├── app/public/                # Fichiers publics uploadés
│   └── logs/                      # Logs de l'application
└── docs/                          # Documentation
```

### 🎯 Contrôleurs Principaux

#### HomeController

-   **Route** : `/`
-   **Fonction** : Page d'accueil, recherche
-   **Méthodes** :
    -   `index()` : Affichage principal
    -   `galerie()` : Galerie des résidences

#### BookingController

-   **Route** : `/booking`
-   **Fonction** : Gestion des réservations
-   **Méthodes** :
    -   `create()` : Formulaire de réservation
    -   `store()` : Création réservation
    -   `show()` : Détails réservation

#### Admin/ResidenceManagementController

-   **Route** : `/admin/residences`
-   **Fonction** : CRUD résidences
-   **Méthodes** : `index()`, `store()`, `update()`, `destroy()`

### 🗃️ Modèles Principaux

#### User

```php
// Relations
- hasMany(Booking::class)
- hasRole() // Spatie Permission

// Attributs principaux
- name, email, phone, address
- email_verified_at, is_active
```

#### Residence

```php
// Relations
- hasMany(Booking::class)
- belongsToMany(Amenity::class)
- hasMany(ResidenceImage::class)

// Attributs principaux
- name, description, short_description
- name_en, description_en, short_description_en
- location, price_per_night, max_guests
- is_active, is_featured
```

#### Booking

```php
// Relations
- belongsTo(User::class)
- belongsTo(Residence::class)

// Attributs principaux
- check_in, check_out, guests
- total_amount, status
- customer_name, customer_email, customer_phone
```

---

## Base de Données

### 🗄️ Schéma Principal

#### Tables Principales

```sql
-- Utilisateurs
users: id, name, email, phone, address, is_active, roles

-- Résidences
residences: id, name, description, location, price_per_night,
           name_en, description_en, is_active, is_featured

-- Réservations
bookings: id, user_id, residence_id, check_in, check_out,
         total_amount, status, customer_info

-- Équipements
amenities: id, name, icon, description

-- Paramètres
settings: id, key, value, type, group
```

#### Relations

```sql
-- Many-to-Many
residence_amenity: residence_id, amenity_id

-- One-to-Many
residence_images: id, residence_id, image_path, is_primary
reviews: id, residence_id, user_id, rating, comment
```

### 📊 Migrations Importantes

#### Résidences avec Traductions

```php
Schema::create('residences', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->text('description');
    $table->string('short_description')->nullable();

    // Traductions
    $table->string('name_en')->nullable();
    $table->text('description_en')->nullable();
    $table->string('short_description_en')->nullable();

    // Détails
    $table->string('location');
    $table->decimal('price_per_night', 10, 2);
    $table->integer('max_guests');
    $table->boolean('is_active')->default(true);
    $table->boolean('is_featured')->default(false);

    $table->timestamps();
});
```

### 🔄 Seeders de Production

#### SettingSeeder

```php
// Paramètres par défaut du site
Setting::create(['key' => 'site_name', 'value' => 'Jatsmanor']);
Setting::create(['key' => 'show_language_selector', 'value' => '1']);
Setting::create(['key' => 'default_language', 'value' => 'fr']);
```

#### CreateAdminUserSeeder

```php
// Création de l'utilisateur administrateur
$admin = User::create([
    'name' => 'Administrateur',
    'email' => 'admin@jatsmanor.ci',
    'password' => Hash::make('password_securise'),
]);
$admin->assignRole('Administrator');
```

---

## API et Intégrations

### 🔌 API Endpoints

#### API Publique

```php
// Routes disponibles
GET /api/residences              // Liste des résidences
GET /api/residences/{id}         // Détails résidence
GET /api/residences/search       // Recherche
POST /api/bookings               // Créer réservation
GET /api/amenities               // Liste équipements
```

#### API Admin (Authentifiée)

```php
GET /api/admin/bookings          // Gestion réservations
PUT /api/admin/bookings/{id}     // Modifier réservation
GET /api/admin/stats             // Statistiques
```

### 🌐 Intégrations Externes

#### DeepL API (Traduction)

```php
// Service de traduction
class ProfessionalTranslationService {
    public function translateText($text, $targetLang = 'EN')
    {
        $response = Http::withHeaders([
            'Authorization' => 'DeepL-Auth-Key ' . config('services.deepl.key')
        ])->post('https://api-free.deepl.com/v2/translate', [
            'text' => $text,
            'target_lang' => $targetLang
        ]);

        return $response->json()['translations'][0]['text'];
    }
}
```

#### Commandes de Traduction

```bash
# Traduire toutes les résidences
php artisan residences:translate

# Forcer la retraduction
php artisan residences:translate --force

# Vérifier les traductions
php artisan residences:check-translations
```

### 📧 Système de Mail

#### Configuration

```php
// Notifications automatiques
- BookingConfirmationNotification
- ContactMessageNotification
- WelcomeNotification

// Templates
- emails/booking-confirmation.blade.php
- emails/contact-message.blade.php
```

---

## Hébergement et Déploiement

### 🌐 Options d'Hébergement

#### 1. Hébergement Partagé (Débutant)

**Recommandations** :

-   **Hostinger** : Plan Business (€3.99/mois)
-   **OVH** : Hébergement Web Pro (€7.99/mois)
-   **Infomaniak** : Hébergement Web (CHF 5.75/mois)

**Configuration** :

```php
// .env pour hébergement partagé
APP_ENV=production
APP_DEBUG=false
DB_CONNECTION=mysql
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=database
```

#### 2. VPS (Recommandé)

**Fournisseurs** :

-   **DigitalOcean** : Droplet 2GB RAM (€12/mois)
-   **Vultr** : Cloud Compute (€12/mois)
-   **Hetzner** : Cloud Server (€4.15/mois)

**Configuration Optimale** :

```bash
# Serveur : Ubuntu 22.04 LTS
# RAM : 4GB
# CPU : 2 vCores
# Stockage : 80GB SSD
# Bande passante : Illimitée
```

#### 3. Cloud Managé (Professionnel)

**Options** :

-   **Laravel Forge** + DigitalOcean
-   **Ploi** + Vultr
-   **AWS Elastic Beanstalk**

### 🚀 Script de Déploiement

#### deploy.sh

```bash
#!/bin/bash
echo "🚀 Déploiement Jatsmanor..."

# Mise à jour du code
git pull origin main

# Maintenance mode
php artisan down

# Dépendances
composer install --no-dev --optimize-autoloader
npm install && npm run build

# Base de données
php artisan migrate --force

# Cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Permissions
chown -R www-data:www-data storage bootstrap/cache

# Fin maintenance
php artisan up

echo "✅ Déploiement terminé!"
```

#### Automatisation avec GitHub Actions

```yaml
name: Deploy to Production
on:
    push:
        branches: [main]

jobs:
    deploy:
        runs-on: ubuntu-latest
        steps:
            - name: Deploy to server
              uses: appleboy/ssh-action@v0.1.5
              with:
                  host: ${{ secrets.HOST }}
                  username: ${{ secrets.USERNAME }}
                  key: ${{ secrets.PRIVATE_KEY }}
                  script: |
                      cd /var/www/jatsmanor
                      ./deploy.sh
```

### 📦 Conteneurisation (Docker)

#### Dockerfile

```dockerfile
FROM php:8.1-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy application
COPY . .

# Install dependencies
RUN composer install --optimize-autoloader --no-dev

EXPOSE 9000
CMD ["php-fpm"]
```

#### docker-compose.yml

```yaml
version: "3.8"
services:
    app:
        build: .
        volumes:
            - ./storage:/var/www/storage

    nginx:
        image: nginx:alpine
        ports:
            - "80:80"
            - "443:443"
        volumes:
            - ./nginx.conf:/etc/nginx/conf.d/default.conf

    mysql:
        image: mysql:8.0
        environment:
            MYSQL_DATABASE: jatsmanor
            MYSQL_ROOT_PASSWORD: root_password

    redis:
        image: redis:alpine
```

---

## Maintenance et Monitoring

### 📊 Monitoring

#### Logs de l'Application

```bash
# Localisation des logs
/var/www/jatsmanor/storage/logs/

# Logs principaux
laravel.log           # Erreurs générales
laravel-2024-01-01.log # Logs quotidiens

# Surveillance en temps réel
tail -f storage/logs/laravel.log
```

#### Métriques Importantes

```php
// Commandes de monitoring
php artisan queue:work --verbose    // Surveillance des queues
php artisan schedule:run            // Tâches planifiées
php artisan horizon                 // Interface Redis Queue
```

#### Alertes Automatiques

```php
// Dans AppServiceProvider
public function boot()
{
    // Alerte si erreurs critiques
    Log::listen(function ($message) {
        if ($message->level === 'emergency') {
            Mail::to('admin@jatsmanor.ci')->send(new CriticalErrorAlert($message));
        }
    });
}
```

### 🔄 Sauvegardes

#### Script de Sauvegarde Automatique

```bash
#!/bin/bash
# backup.sh

DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/var/backups/jatsmanor"

# Sauvegarde base de données
mysqldump -u jatsmanor_user -p jatsmanor > $BACKUP_DIR/db_$DATE.sql

# Sauvegarde fichiers uploadés
tar -czf $BACKUP_DIR/files_$DATE.tar.gz /var/www/jatsmanor/storage/app/public

# Nettoyage (garder 30 jours)
find $BACKUP_DIR -name "*.sql" -mtime +30 -delete
find $BACKUP_DIR -name "*.tar.gz" -mtime +30 -delete

echo "✅ Sauvegarde terminée: $DATE"
```

#### Cron Job

```bash
# Éditer crontab
crontab -e

# Sauvegarde quotidienne à 2h du matin
0 2 * * * /var/www/jatsmanor/backup.sh
```

#### Sauvegarde Cloud

```php
// Utilisation d'AWS S3
use Illuminate\Support\Facades\Storage;

// Upload vers S3
Storage::disk('s3')->put('backups/db_' . date('Y-m-d') . '.sql', $databaseDump);
```

### 🔧 Maintenance Préventive

#### Tâches Quotidiennes

```php
// Dans Console/Kernel.php
protected function schedule(Schedule $schedule)
{
    // Nettoyage des logs
    $schedule->command('log:clear')->daily();

    // Nettoyage du cache
    $schedule->command('cache:prune-stale-tags')->hourly();

    // Sauvegarde
    $schedule->exec('/var/www/jatsmanor/backup.sh')->dailyAt('02:00');

    // Vérification de la santé du système
    $schedule->command('health:check')->everyFiveMinutes();
}
```

#### Commandes de Maintenance

```bash
# Nettoyage complet
php artisan optimize:clear    # Vide tous les caches
php artisan storage:link      # Recrée les liens symboliques
php artisan queue:restart     # Redémarre les workers

# Vérifications
php artisan route:list        # Vérifier les routes
php artisan config:show       # Vérifier la configuration
```

### 📈 Performance Monitoring

#### Outils Recommandés

-   **New Relic** : Monitoring APM complet
-   **Sentry** : Tracking des erreurs
-   **Laravel Telescope** : Debug en développement
-   **Blackfire** : Profiling de performance

#### Configuration Sentry

```php
// config/sentry.php
'dsn' => env('SENTRY_LARAVEL_DSN'),
'environment' => env('APP_ENV'),
'traces_sample_rate' => 0.1,
```

---

## Sécurité

### 🔒 Mesures de Sécurité

#### Configuration SSL/TLS

```bash
# Installation Certbot
sudo apt install certbot python3-certbot-nginx

# Obtenir certificat
sudo certbot --nginx -d votre-domaine.com

# Renouvellement automatique
sudo crontab -e
0 12 * * * /usr/bin/certbot renew --quiet
```

#### Headers de Sécurité

```php
// Dans Middleware/SecurityHeaders.php
public function handle($request, Closure $next)
{
    $response = $next($request);

    $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
    $response->headers->set('X-Content-Type-Options', 'nosniff');
    $response->headers->set('X-XSS-Protection', '1; mode=block');
    $response->headers->set('Strict-Transport-Security', 'max-age=31536000');

    return $response;
}
```

#### Protection CSRF

```php
// Automatique dans Laravel
@csrf // Dans les formulaires Blade

// Vérification AJAX
headers: {
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
}
```

### 🛡️ Authentification et Autorisation

#### Système de Rôles (Spatie)

```php
// Rôles disponibles
- Administrator : Accès complet
- Client : Réservations uniquement

// Permissions
- manage-residences
- manage-bookings
- manage-users
- view-admin-dashboard
```

#### Middleware de Protection

```php
// Protection des routes admin
Route::middleware(['auth', 'role:Administrator'])->group(function () {
    Route::resource('admin/residences', ResidenceController::class);
});
```

### 🔐 Gestion des Mots de Passe

#### Politique des Mots de Passe

```php
// config/auth.php
'passwords' => [
    'users' => [
        'provider' => 'users',
        'table' => 'password_reset_tokens',
        'expire' => 60,
        'throttle' => 60,
    ],
],
```

#### Hashage Sécurisé

```php
// Utilisation d'Argon2ID
'driver' => 'argon2id',
'argon' => [
    'memory' => 65536,
    'threads' => 1,
    'time' => 4,
],
```

### 🚨 Protection contre les Attaques

#### Rate Limiting

```php
// Dans RouteServiceProvider
RateLimiter::for('api', function (Request $request) {
    return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
});

// Application
Route::middleware(['throttle:api'])->group(function () {
    // Routes API
});
```

#### Validation et Sanitisation

```php
// Validation stricte
$request->validate([
    'email' => 'required|email|filter:validate_email',
    'name' => 'required|string|max:255|regex:/^[a-zA-ZÀ-ÿ\s]+$/',
    'phone' => 'required|regex:/^\+?[0-9\s\-\(\)]+$/',
]);

// Sanitisation automatique
protected $casts = [
    'description' => 'string',
];
```

---

## Performance et Optimisation

### ⚡ Optimisations Backend

#### Cache Configuration

```php
// config/cache.php
'default' => env('CACHE_DRIVER', 'redis'),

'stores' => [
    'redis' => [
        'driver' => 'redis',
        'connection' => 'cache',
        'lock_connection' => 'default',
    ],
],
```

#### Optimisations Eloquent

```php
// Eager Loading
$residences = Residence::with(['amenities', 'images'])->get();

// Pagination
$bookings = Booking::paginate(20);

// Index de base de données
Schema::table('bookings', function (Blueprint $table) {
    $table->index(['check_in', 'check_out']);
    $table->index(['status', 'created_at']);
});
```

#### Queue Jobs

```php
// Jobs asynchrones
dispatch(new SendBookingConfirmation($booking));

// Configuration Redis
QUEUE_CONNECTION=redis
```

### 🎨 Optimisations Frontend

#### Vite Configuration

```javascript
// vite.config.js
export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
    ],
    build: {
        rollupOptions: {
            output: {
                manualChunks: {
                    vendor: ["alpinejs"],
                },
            },
        },
    },
});
```

#### Optimisation Images

```php
// Intervention Image
use Intervention\Image\Facades\Image;

public function uploadImage($file)
{
    $image = Image::make($file)
        ->fit(800, 600)
        ->encode('webp', 85);

    return $image->save(storage_path('app/public/residences/' . $filename));
}
```

#### Compression et Minification

```bash
# Build production
npm run build

# Résultat
- CSS minifié et purgé
- JS bundlé et compressé
- Images optimisées
```

### 📊 Monitoring des Performances

#### Laravel Debugbar (Développement)

```php
composer require barryvdh/laravel-debugbar --dev
```

#### Profiling Production

```php
// Utilisation d'Horizon pour les queues
php artisan horizon

// Monitoring des requêtes lentes
DB_SLOW_QUERY_LOG=true
DB_SLOW_QUERY_TIME=1000
```

---

## Troubleshooting

### 🔍 Problèmes Courants

#### 1. Erreur 500 - Internal Server Error

```bash
# Vérifier les logs
tail -f storage/logs/laravel.log

# Causes communes
- Permissions incorrectes sur storage/
- Configuration .env manquante
- Erreur de syntaxe PHP
- Extension PHP manquante

# Solutions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
php artisan config:clear
```

#### 2. Problèmes de Base de Données

```bash
# Test de connexion
php artisan tinker
DB::connection()->getPdo();

# Migration bloquée
php artisan migrate:status
php artisan migrate:rollback
php artisan migrate:fresh --seed
```

#### 3. Problèmes de Cache

```bash
# Vider tous les caches
php artisan optimize:clear

# Cache spécifique
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
```

#### 4. Erreurs de Permissions

```bash
# Permissions Laravel
sudo chown -R $USER:www-data storage
sudo chown -R $USER:www-data bootstrap/cache
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# SELinux (CentOS/RHEL)
setsebool -P httpd_can_network_connect 1
setsebool -P httpd_unified 1
```

### 🐛 Debug et Logs

#### Configuration Debug

```php
// .env développement
APP_DEBUG=true
LOG_LEVEL=debug

// .env production
APP_DEBUG=false
LOG_LEVEL=error
```

#### Logs Personnalisés

```php
// Dans les contrôleurs
Log::info('Nouvelle réservation', ['booking_id' => $booking->id]);
Log::error('Erreur paiement', ['error' => $e->getMessage()]);

// Channels personnalisés
'channels' => [
    'bookings' => [
        'driver' => 'single',
        'path' => storage_path('logs/bookings.log'),
        'level' => 'info',
    ],
],
```

#### Outils de Debug

```php
// Dump and Die
dd($variable);

// Dump sans arrêt
dump($variable);

// Log de debug
logger('Debug info', ['data' => $data]);
```

### 🔧 Commandes Utiles

#### Artisan Commands

```bash
# État de l'application
php artisan about
php artisan env
php artisan route:list
php artisan config:show

# Base de données
php artisan migrate:status
php artisan db:show
php artisan model:show User

# Cache et optimisation
php artisan optimize
php artisan optimize:clear

# Queues
php artisan queue:work
php artisan queue:failed
php artisan queue:retry all

# Maintenance
php artisan down
php artisan up
```

#### Composer Commands

```bash
# Mise à jour
composer update
composer install --optimize-autoloader --no-dev

# Autoload
composer dump-autoload

# Audit sécurité
composer audit
```

---

## 📞 Support et Maintenance

### 🆘 Contacts Techniques

-   **Email** : dev@jatsmanor.ci
-   **Documentation** : https://docs.jatsmanor.ci
-   **Repository** : https://github.com/jatsmanor/platform
-   **Issues** : https://github.com/jatsmanor/platform/issues

### 📅 Planning de Maintenance

#### Maintenance Régulière

-   **Quotidienne** : Sauvegardes automatiques
-   **Hebdomadaire** : Mise à jour sécurité
-   **Mensuelle** : Audit complet
-   **Trimestrielle** : Optimisation performance

#### Mise à Jour

```bash
# Procédure de mise à jour
1. Sauvegarde complète
2. Test en environnement staging
3. Maintenance mode
4. Déploiement
5. Tests post-déploiement
6. Fin maintenance mode
```

---

**© 2024 Jatsmanor - Documentation Technique**

_Version 1.0 - Dernière mise à jour : Janvier 2024_
