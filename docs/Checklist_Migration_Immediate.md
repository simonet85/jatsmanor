# ✅ CHECKLIST MIGRATION JATSMANOR - ÉTAPES IMMÉDIATES

## 🎯 **État actuel de la préparation**

### ✅ **Complété**

-   [x] Backup base de données créé (jatsmanor_backup.sql - 111KB)
-   [x] Configuration .env.hostinger préparée
-   [x] Caches Laravel nettoyés
-   [x] Versions compatibles vérifiées (PHP 8.4.3, Laravel 10)

### ⚠️ **À finaliser localement**

```powershell
# 1. Activer l'extension fileinfo PHP (requis)
# Éditer C:\laragon\bin\php\php-8.4.3-Win32-vs17-x64\php.ini
# Décommenter ou ajouter: extension=fileinfo

# 2. Réinstaller dépendances après correction
composer install --optimize-autoloader --no-dev

# 3. Compiler assets (si npm disponible)
npm install
npm run production

# 4. Créer archive de déploiement
# Inclure: app/, bootstrap/, config/, database/, public/, resources/, routes/, storage/, composer.json, composer.lock, artisan
# Exclure: node_modules/, vendor/, .git/, storage/logs/, .env
```

---

## 🏗️ **PROCHAINES ÉTAPES : Setup Hostinger**

### **Étape 2 : Achat hébergement Hostinger (15 min)**

1. **Aller sur** : https://www.hostinger.fr
2. **Choisir** : Plan Business (3.99€/mois)
3. **Domaine** : jatsmanor.com (ou votre choix)
4. **Options** :
    - ✅ SSL gratuit (inclus)
    - ✅ Backups quotidiens (inclus)
    - ✅ Email professionnel (inclus)

### **Étape 3 : Configuration initiale Hostinger (10 min)**

#### **Accès hPanel**

```yaml
1. Connexion: hpanel.hostinger.com
2. Sites Web > Gérer
3. Fichiers > Gestionnaire de fichiers
4. Base de données > MySQL
```

#### **Configuration DNS**

```dns
# Chez le registrar du domaine
Type: A
Nom: @
Valeur: [IP fournie par Hostinger]

Type: A
Nom: www
Valeur: [IP fournie par Hostinger]
```

### **Étape 4 : Upload et installation (30 min)**

#### **4.1 Création base de données**

```sql
-- Dans hPanel > Base de données > Créer
Nom: u[votre_id]_jatsmanor
Utilisateur: u[votre_id]_jatsuser
Mot de passe: [Générer sécurisé]
```

#### **4.2 Upload fichiers**

```yaml
# Via Gestionnaire de fichiers Hostinger
1. Supprimer contenu par défaut public_html/
2. Upload archive projet (sans vendor/, node_modules/)
3. Extraire dans public_html/
4. Organiser structure Laravel
```

#### **4.3 Configuration .env production**

```bash
# Copier .env.hostinger vers .env sur serveur
# Modifier avec vrais identifiants base de données
# Générer nouvelle APP_KEY si nécessaire
```

#### **4.4 Installation dépendances**

```bash
# Via SSH ou cron job temporaire
cd /home/u[id]/domains/jatsmanor.com/public_html
wget https://getcomposer.org/composer.phar
php composer.phar install --optimize-autoloader --no-dev
```

#### **4.5 Migration base de données**

```bash
# Import via phpMyAdmin ou commande
mysql -u username -p database_name < jatsmanor_backup.sql

# Ou via interface phpMyAdmin
# hPanel > Base de données > Gérer > phpMyAdmin > Import
```

#### **4.6 Configuration Laravel**

```bash
php artisan key:generate --force
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link

# Permissions
chmod 755 storage/ -R
chmod 755 bootstrap/cache/ -R
```

---

## 🔧 **Configuration serveur .htaccess**

### **public_html/.htaccess**

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On

    # Redirection vers public/ pour Laravel
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

### **public_html/public/.htaccess**

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>

# Security headers
<IfModule mod_headers.c>
    Header always set X-Content-Type-Options nosniff
    Header always set X-Frame-Options SAMEORIGIN
    Header always set X-XSS-Protection "1; mode=block"
</IfModule>

# Compression
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/plain
    AddOutputFilterByType DEFLATE text/html
    AddOutputFilterByType DEFLATE text/xml
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE application/xml
    AddOutputFilterByType DEFLATE application/xhtml+xml
    AddOutputFilterByType DEFLATE application/rss+xml
    AddOutputFilterByType DEFLATE application/javascript
    AddOutputFilterByType DEFLATE application/x-javascript
</IfModule>

# Cache statique
<IfModule mod_expires.c>
    ExpiresActive on
    ExpiresByType text/css "access plus 1 year"
    ExpiresByType application/javascript "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType image/jpg "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/gif "access plus 1 year"
    ExpiresByType image/webp "access plus 1 year"
</IfModule>
```

---

## ✅ **Tests post-migration**

### **Tests fonctionnels**

```bash
# 1. Accès site
curl -I https://jatsmanor.com
# Doit retourner 200 OK

# 2. Test base de données
# Via interface: connexion, inscription, propriétés

# 3. Test email
# Formulaire contact, notifications

# 4. Test upload images
# Ajout photos propriétés
```

### **Performance**

```yaml
Tools à utiliser:
    - Google PageSpeed Insights
    - GTmetrix.com
    - Pingdom Tools

Objectifs:
    - Score > 80/100
    - Temps chargement < 3s
    - Uptime > 99%
```

---

## 🆘 **Dépannage courant**

### **Erreur 500**

```bash
# Vérifier logs
tail -f storage/logs/laravel.log

# Permissions
chmod 755 storage/ bootstrap/cache/ -R

# Cache
php artisan config:clear
```

### **Base de données**

```bash
# Test connexion
mysql -h localhost -u username -p database

# Vérifier .env
DB_HOST=localhost
DB_DATABASE=u123456_jatsmanor
```

### **Images manquantes**

```bash
# Symlink storage
php artisan storage:link

# Permissions
chmod 755 storage/app/public/ -R
```

---

## 📞 **Support et ressources**

### **Documentation Hostinger**

-   Guide Laravel: https://www.hostinger.com/tutorials/how-to-install-laravel
-   Support: Chat 24/7 (français disponible)
-   Base de connaissances: help.hostinger.com

### **Monitoring recommandé**

-   **UptimeRobot** (gratuit): Surveillance 24/7
-   **Google Analytics**: Trafic et conversions
-   **Search Console**: SEO et indexation

---

## 🎯 **Timeline réaliste**

-   **Aujourd'hui** : Finir préparation locale (1-2h)
-   **Demain** : Achat Hostinger + Setup initial (1h)
-   **J+2** : Upload et configuration (2-3h)
-   **J+3** : Tests et optimisations (1-2h)
-   **J+4** : DNS propagation + Go Live ! 🚀

---

**Total temps estimé : 6-8 heures réparties sur 4 jours**
**Coût : 3.99€/mois (première année)**
**Résultat : Plateforme Jatsmanor en ligne et opérationnelle !**
