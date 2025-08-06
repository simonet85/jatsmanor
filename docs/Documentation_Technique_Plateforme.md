# Documentation Technique - Plateforme de Réservation Jatsmanor

**Architecture & Implémentation - Application de Location de Résidences**

---

## 🏗️ **Architecture système complète**

### **Vue d'ensemble de l'architecture**

```
┌─────────────────────────────────────────────────────────────────┐
│                    FRONTEND (Multi-device)                     │
├─────────────────┬─────────────────┬─────────────────────────────┤
│   Web App       │   Mobile App    │      Admin Panel           │
│   (Vue.js/Blade)│   (React Native)│      (Laravel Nova)        │
└─────────────────┴─────────────────┴─────────────────────────────┘
                              │
┌─────────────────────────────────────────────────────────────────┐
│                      API LAYER (Laravel)                       │
├─────────────────┬─────────────────┬─────────────────────────────┤
│  Booking API    │ Property API    │    User Management API     │
│  Payment API    │ Search API      │    Notification API        │
└─────────────────┴─────────────────┴─────────────────────────────┘
                              │
┌─────────────────────────────────────────────────────────────────┐
│                    CORE SERVICES                               │
├─────────────────┬─────────────────┬─────────────────────────────┤
│ Booking Engine  │ Payment Gateway │   Translation Service       │
│ Search Engine   │ Notification    │   Analytics Service         │
│ Calendar Sync   │ Email Service   │   Security Service          │
└─────────────────┴─────────────────┴─────────────────────────────┘
                              │
┌─────────────────────────────────────────────────────────────────┐
│                   DATA LAYER                                   │
├─────────────────┬─────────────────┬─────────────────────────────┤
│   MySQL         │     Redis       │      File Storage           │
│ (Primary DB)    │   (Cache)       │    (Images/Documents)       │
│                 │   (Sessions)    │                             │
└─────────────────┴─────────────────┴─────────────────────────────┘
                              │
┌─────────────────────────────────────────────────────────────────┐
│                 EXTERNAL SERVICES                              │
├─────────────────┬─────────────────┬─────────────────────────────┤
│   DeepL API     │  Payment APIs   │     Communication           │
│ (Translation)   │ (Stripe, etc.)  │   (SMS, Email, Push)        │
│   Google Maps   │  Mobile Money   │     Social Login            │
└─────────────────┴─────────────────┴─────────────────────────────┘
```

---

## 💾 **Modèle de données**

### **Schéma de base de données principal**

#### **Table `users`**

```sql
CREATE TABLE users (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    uuid VARCHAR(36) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    phone VARCHAR(20) UNIQUE,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    avatar VARCHAR(255),
    date_of_birth DATE,
    nationality VARCHAR(3), -- Code ISO pays
    preferred_language ENUM('fr', 'en') DEFAULT 'fr',
    user_type ENUM('guest', 'host', 'admin') DEFAULT 'guest',
    verification_status ENUM('pending', 'verified', 'rejected') DEFAULT 'pending',
    phone_verified_at TIMESTAMP NULL,
    email_verified_at TIMESTAMP NULL,
    identity_verified_at TIMESTAMP NULL,
    is_active BOOLEAN DEFAULT TRUE,
    last_login_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_email (email),
    INDEX idx_phone (phone),
    INDEX idx_user_type (user_type),
    INDEX idx_verification_status (verification_status)
);
```

#### **Table `properties` (Résidences/Chambres)**

```sql
CREATE TABLE properties (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    uuid VARCHAR(36) UNIQUE NOT NULL,
    host_id BIGINT NOT NULL,

    -- Informations de base
    title VARCHAR(255) NOT NULL,
    title_en VARCHAR(255),
    description TEXT NOT NULL,
    description_en TEXT,
    short_description TEXT,
    short_description_en TEXT,

    -- Localisation
    country VARCHAR(3) DEFAULT 'CIV',
    city VARCHAR(100) NOT NULL,
    district VARCHAR(100), -- Quartier (Cocody, Plateau, etc.)
    address TEXT NOT NULL,
    latitude DECIMAL(10, 8),
    longitude DECIMAL(11, 8),

    -- Caractéristiques
    property_type ENUM('villa', 'apartment', 'house', 'room', 'studio') NOT NULL,
    accommodation_type ENUM('entire_place', 'private_room', 'shared_room') NOT NULL,
    max_guests INT NOT NULL,
    bedrooms INT NOT NULL,
    bathrooms INT NOT NULL,
    beds INT NOT NULL,

    -- Tarification (en FCFA)
    base_price DECIMAL(10, 2) NOT NULL,
    weekly_discount DECIMAL(5, 2) DEFAULT 0, -- Pourcentage
    monthly_discount DECIMAL(5, 2) DEFAULT 0,
    cleaning_fee DECIMAL(10, 2) DEFAULT 0,
    security_deposit DECIMAL(10, 2) DEFAULT 0,
    extra_guest_fee DECIMAL(10, 2) DEFAULT 0,

    -- Règles
    minimum_stay INT DEFAULT 1, -- Nuits minimum
    maximum_stay INT DEFAULT 365,
    advance_booking_days INT DEFAULT 0,
    instant_booking BOOLEAN DEFAULT FALSE,

    -- Statut
    status ENUM('draft', 'pending', 'active', 'inactive', 'suspended') DEFAULT 'draft',
    featured BOOLEAN DEFAULT FALSE,

    -- Métadonnées
    view_count INT DEFAULT 0,
    booking_count INT DEFAULT 0,
    average_rating DECIMAL(3, 2) DEFAULT 0,
    review_count INT DEFAULT 0,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (host_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_host_id (host_id),
    INDEX idx_property_type (property_type),
    INDEX idx_city_district (city, district),
    INDEX idx_price_range (base_price),
    INDEX idx_guests (max_guests),
    INDEX idx_status (status),
    INDEX idx_location (latitude, longitude),
    FULLTEXT idx_search (title, description, district)
);
```

#### **Table `bookings` (Réservations)**

```sql
CREATE TABLE bookings (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    uuid VARCHAR(36) UNIQUE NOT NULL,
    property_id BIGINT NOT NULL,
    guest_id BIGINT NOT NULL,

    -- Dates
    check_in DATE NOT NULL,
    check_out DATE NOT NULL,
    nights INT NOT NULL,

    -- Invités
    guests_count INT NOT NULL,
    adults_count INT NOT NULL,
    children_count INT DEFAULT 0,

    -- Tarification
    base_amount DECIMAL(10, 2) NOT NULL,
    cleaning_fee DECIMAL(10, 2) DEFAULT 0,
    service_fee DECIMAL(10, 2) NOT NULL, -- Commission plateforme
    taxes DECIMAL(10, 2) DEFAULT 0,
    total_amount DECIMAL(10, 2) NOT NULL,
    currency VARCHAR(3) DEFAULT 'XOF',

    -- Statut
    status ENUM('pending', 'confirmed', 'checked_in', 'checked_out', 'cancelled', 'completed') DEFAULT 'pending',
    payment_status ENUM('pending', 'partial', 'paid', 'refunded') DEFAULT 'pending',

    -- Communication
    guest_message TEXT,
    host_response TEXT,
    special_requests TEXT,

    -- Métadonnées
    booking_source VARCHAR(50) DEFAULT 'web', -- web, mobile, api
    guest_language VARCHAR(2) DEFAULT 'fr',

    -- Dates importantes
    confirmed_at TIMESTAMP NULL,
    cancelled_at TIMESTAMP NULL,
    checked_in_at TIMESTAMP NULL,
    checked_out_at TIMESTAMP NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE,
    FOREIGN KEY (guest_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_property_dates (property_id, check_in, check_out),
    INDEX idx_guest_id (guest_id),
    INDEX idx_status (status),
    INDEX idx_check_in (check_in),
    INDEX idx_booking_source (booking_source)
);
```

#### **Table `payments` (Paiements)**

```sql
CREATE TABLE payments (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    uuid VARCHAR(36) UNIQUE NOT NULL,
    booking_id BIGINT NOT NULL,

    -- Montants
    amount DECIMAL(10, 2) NOT NULL,
    currency VARCHAR(3) DEFAULT 'XOF',
    exchange_rate DECIMAL(10, 6) DEFAULT 1,

    -- Méthode de paiement
    payment_method ENUM('card', 'mobile_money', 'bank_transfer', 'cash') NOT NULL,
    payment_provider VARCHAR(50), -- stripe, orange_money, mtn_money, etc.
    transaction_id VARCHAR(255),

    -- Statut
    status ENUM('pending', 'processing', 'completed', 'failed', 'refunded') DEFAULT 'pending',

    -- Métadonnées
    gateway_response JSON,
    failure_reason TEXT,
    processed_at TIMESTAMP NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,
    INDEX idx_booking_id (booking_id),
    INDEX idx_status (status),
    INDEX idx_payment_method (payment_method),
    INDEX idx_transaction_id (transaction_id)
);
```

#### **Table `reviews` (Évaluations)**

```sql
CREATE TABLE reviews (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    booking_id BIGINT NOT NULL,
    reviewer_id BIGINT NOT NULL,
    reviewee_id BIGINT NOT NULL, -- Peut être host ou guest
    reviewer_type ENUM('guest', 'host') NOT NULL,

    -- Évaluations (1-5)
    overall_rating TINYINT NOT NULL,
    cleanliness_rating TINYINT,
    communication_rating TINYINT,
    location_rating TINYINT,
    value_rating TINYINT,
    accuracy_rating TINYINT,

    -- Commentaires
    comment TEXT,
    comment_en TEXT, -- Traduit automatiquement

    -- Réponse
    response TEXT,
    response_en TEXT,
    response_date TIMESTAMP NULL,

    -- Statut
    is_public BOOLEAN DEFAULT TRUE,
    is_verified BOOLEAN DEFAULT FALSE,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,
    FOREIGN KEY (reviewer_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (reviewee_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_booking_id (booking_id),
    INDEX idx_reviewee_rating (reviewee_id, overall_rating),
    INDEX idx_public_reviews (is_public, created_at)
);
```

#### **Table `amenities` (Équipements)**

```sql
CREATE TABLE amenities (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    name_en VARCHAR(100),
    category VARCHAR(50) NOT NULL, -- wifi, kitchen, parking, etc.
    icon VARCHAR(50),
    is_active BOOLEAN DEFAULT TRUE,
    sort_order INT DEFAULT 0
);

CREATE TABLE property_amenities (
    property_id BIGINT NOT NULL,
    amenity_id BIGINT NOT NULL,

    PRIMARY KEY (property_id, amenity_id),
    FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE,
    FOREIGN KEY (amenity_id) REFERENCES amenities(id) ON DELETE CASCADE
);
```

---

## 🔧 **Services et composants métier**

### **1. Service de réservation**

```php
class BookingService
{
    public function __construct(
        private PaymentService $paymentService,
        private NotificationService $notificationService,
        private CalendarService $calendarService,
        private ProfessionalTranslationService $translationService
    ) {}

    public function createBooking(CreateBookingRequest $request): Booking
    {
        DB::beginTransaction();

        try {
            // 1. Vérifier disponibilité
            $this->validateAvailability($request->property_id, $request->check_in, $request->check_out);

            // 2. Calculer tarification
            $pricing = $this->calculatePricing($request);

            // 3. Créer la réservation
            $booking = Booking::create([
                'property_id' => $request->property_id,
                'guest_id' => $request->guest_id,
                'check_in' => $request->check_in,
                'check_out' => $request->check_out,
                'guests_count' => $request->guests_count,
                'total_amount' => $pricing['total'],
                'guest_message' => $request->message,
                'guest_language' => app()->getLocale(),
                'status' => 'pending'
            ]);

            // 4. Bloquer les dates dans le calendrier
            $this->calendarService->blockDates($request->property_id, $request->check_in, $request->check_out);

            // 5. Initier le paiement
            $payment = $this->paymentService->initializePayment($booking, $request->payment_method);

            // 6. Notifications multilingues
            $this->sendBookingNotifications($booking);

            DB::commit();
            return $booking;

        } catch (\Exception $e) {
            DB::rollback();
            throw new BookingException('Booking creation failed: ' . $e->getMessage());
        }
    }

    private function sendBookingNotifications(Booking $booking): void
    {
        $guest = $booking->guest;
        $property = $booking->property;
        $host = $property->host;

        // Email au client dans sa langue
        $guestLanguage = $guest->preferred_language ?? 'fr';
        $this->notificationService->sendBookingConfirmation($guest, $booking, $guestLanguage);

        // Email au propriétaire
        $hostLanguage = $host->preferred_language ?? 'fr';
        $this->notificationService->sendNewBookingAlert($host, $booking, $hostLanguage);
    }
}
```

### **2. Moteur de recherche**

```php
class PropertySearchService
{
    public function search(SearchRequest $request): LengthAwarePaginator
    {
        $query = Property::query()
            ->with(['amenities', 'photos', 'host'])
            ->where('status', 'active');

        // Filtres géographiques
        if ($request->city) {
            $query->where('city', $request->city);
        }

        if ($request->district) {
            $query->where('district', $request->district);
        }

        // Filtres de dates et disponibilité
        if ($request->check_in && $request->check_out) {
            $query->whereDoesntHave('bookings', function ($bookingQuery) use ($request) {
                $bookingQuery->where('status', '!=', 'cancelled')
                    ->where(function ($dateQuery) use ($request) {
                        $dateQuery->whereBetween('check_in', [$request->check_in, $request->check_out])
                            ->orWhereBetween('check_out', [$request->check_in, $request->check_out])
                            ->orWhere(function ($overlapQuery) use ($request) {
                                $overlapQuery->where('check_in', '<=', $request->check_in)
                                    ->where('check_out', '>=', $request->check_out);
                            });
                    });
            });
        }

        // Filtres de capacité
        if ($request->guests) {
            $query->where('max_guests', '>=', $request->guests);
        }

        // Filtres de prix
        if ($request->min_price) {
            $query->where('base_price', '>=', $request->min_price);
        }

        if ($request->max_price) {
            $query->where('base_price', '<=', $request->max_price);
        }

        // Filtres d'équipements
        if ($request->amenities) {
            $query->whereHas('amenities', function ($amenityQuery) use ($request) {
                $amenityQuery->whereIn('amenities.id', $request->amenities);
            }, '=', count($request->amenities));
        }

        // Recherche textuelle multilingue
        if ($request->query) {
            $searchTerm = $request->query;
            $query->where(function ($textQuery) use ($searchTerm) {
                $textQuery->whereRaw('MATCH(title, description, district) AGAINST(? IN NATURAL LANGUAGE MODE)', [$searchTerm])
                    ->orWhere('title_en', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('description_en', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Tri
        switch ($request->sort_by) {
            case 'price_low':
                $query->orderBy('base_price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('base_price', 'desc');
                break;
            case 'rating':
                $query->orderBy('average_rating', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('featured', 'desc')
                    ->orderBy('average_rating', 'desc');
        }

        return $query->paginate($request->per_page ?? 20);
    }
}
```

### **3. Service de paiement intégré**

```php
class PaymentService
{
    public function initializePayment(Booking $booking, string $paymentMethod): Payment
    {
        $payment = Payment::create([
            'booking_id' => $booking->id,
            'amount' => $booking->total_amount,
            'currency' => 'XOF',
            'payment_method' => $paymentMethod,
            'status' => 'pending'
        ]);

        // Router vers le bon provider
        $result = match($paymentMethod) {
            'card' => $this->processCardPayment($payment),
            'mobile_money' => $this->processMobileMoneyPayment($payment),
            'bank_transfer' => $this->processBankTransferPayment($payment),
            default => throw new InvalidPaymentMethodException()
        };

        $payment->update([
            'transaction_id' => $result['transaction_id'],
            'payment_provider' => $result['provider'],
            'gateway_response' => $result['response']
        ]);

        return $payment;
    }

    private function processCardPayment(Payment $payment): array
    {
        // Intégration Stripe ou autre gateway international
        $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));

        $intent = $stripe->paymentIntents->create([
            'amount' => $payment->amount * 100, // Stripe expects cents
            'currency' => 'usd', // Convert XOF to USD
            'payment_method_types' => ['card'],
            'metadata' => [
                'booking_id' => $payment->booking_id,
                'payment_id' => $payment->id
            ]
        ]);

        return [
            'transaction_id' => $intent->id,
            'provider' => 'stripe',
            'response' => $intent->toArray()
        ];
    }

    private function processMobileMoneyPayment(Payment $payment): array
    {
        // Intégration avec Orange Money, MTN, Moov
        $booking = $payment->booking;
        $guest = $booking->guest;

        // Exemple avec Orange Money API
        $orangeApi = new OrangeMoneyApi(config('services.orange_money.api_key'));

        $transaction = $orangeApi->createPayment([
            'amount' => $payment->amount,
            'currency' => 'XOF',
            'phone_number' => $guest->phone,
            'reference' => 'JATS-' . $booking->uuid,
            'description' => "Réservation {$booking->property->title}"
        ]);

        return [
            'transaction_id' => $transaction['transaction_id'],
            'provider' => 'orange_money',
            'response' => $transaction
        ];
    }
}
```

### **4. Service de notification multilingue**

```php
class NotificationService
{
    public function __construct(
        private ProfessionalTranslationService $translationService,
        private MailService $mailService,
        private SmsService $smsService
    ) {}

    public function sendBookingConfirmation(User $user, Booking $booking, string $language = 'fr'): void
    {
        $property = $booking->property;

        // Préparer les données dans la langue de l'utilisateur
        $data = [
            'user_name' => $user->first_name,
            'property_title' => $language === 'en' && $property->title_en
                ? $property->title_en
                : $property->title,
            'check_in' => $booking->check_in->format('d/m/Y'),
            'check_out' => $booking->check_out->format('d/m/Y'),
            'total_amount' => number_format($booking->total_amount, 0, ',', ' ') . ' FCFA',
            'booking_reference' => $booking->uuid
        ];

        // Template d'email selon la langue
        $template = $language === 'en'
            ? 'emails.booking_confirmation_en'
            : 'emails.booking_confirmation_fr';

        // Sujet traduit
        $subject = $language === 'en'
            ? "Booking Confirmation - {$data['property_title']}"
            : "Confirmation de réservation - {$data['property_title']}";

        $this->mailService->send($user->email, $subject, $template, $data);

        // SMS de confirmation
        $smsMessage = $language === 'en'
            ? "Booking confirmed! Reference: {$data['booking_reference']}. Check-in: {$data['check_in']}"
            : "Réservation confirmée ! Référence: {$data['booking_reference']}. Arrivée: {$data['check_in']}";

        $this->smsService->send($user->phone, $smsMessage);
    }
}
```

---

## 🔒 **Sécurité et authentification**

### **Système d'authentification multi-niveaux**

```php
class AuthenticationService
{
    public function register(RegisterRequest $request): User
    {
        $user = User::create([
            'uuid' => Str::uuid(),
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'preferred_language' => app()->getLocale(),
            'user_type' => $request->user_type ?? 'guest'
        ]);

        // Envoi email de vérification
        $this->sendEmailVerification($user);

        // Envoi SMS de vérification
        $this->sendPhoneVerification($user);

        return $user;
    }

    public function verifyIdentity(User $user, IdentityVerificationRequest $request): bool
    {
        // Vérification document d'identité
        $idDocument = $this->processIdentityDocument($request->identity_document);

        // Vérification selfie avec document
        $selfieVerification = $this->verifySelfieWithDocument(
            $request->selfie_photo,
            $idDocument
        );

        if ($idDocument['valid'] && $selfieVerification['valid']) {
            $user->update([
                'identity_verified_at' => now(),
                'verification_status' => 'verified'
            ]);

            return true;
        }

        return false;
    }
}
```

### **Middleware de sécurité**

```php
class SecurityMiddleware
{
    public function handle($request, Closure $next)
    {
        // Rate limiting
        $key = 'api_calls_' . $request->ip();
        if (Cache::get($key, 0) > 1000) { // 1000 calls per hour
            return response()->json(['error' => 'Rate limit exceeded'], 429);
        }
        Cache::increment($key);
        Cache::expire($key, 3600);

        // Validation géographique pour certaines actions
        if ($this->isRestrictedAction($request)) {
            $country = $this->getCountryFromIP($request->ip());
            if (!in_array($country, ['CI', 'FR', 'US', 'CA', 'GB'])) {
                Log::warning('Suspicious access from country: ' . $country, [
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ]);
            }
        }

        return $next($request);
    }
}
```

---

## 📊 **Analytics et Business Intelligence**

### **Service d'analytics métier**

```php
class BusinessAnalyticsService
{
    public function getBookingMetrics(string $period = '30d'): array
    {
        $startDate = now()->subDays(30);

        return [
            'total_bookings' => $this->getTotalBookings($startDate),
            'total_revenue' => $this->getTotalRevenue($startDate),
            'average_booking_value' => $this->getAverageBookingValue($startDate),
            'occupancy_rate' => $this->getOccupancyRate($startDate),
            'top_properties' => $this->getTopProperties($startDate),
            'guest_demographics' => $this->getGuestDemographics($startDate),
            'seasonal_trends' => $this->getSeasonalTrends(),
            'conversion_funnel' => $this->getConversionFunnel($startDate)
        ];
    }

    private function getGuestDemographics(Carbon $startDate): array
    {
        return [
            'by_country' => Booking::join('users', 'bookings.guest_id', '=', 'users.id')
                ->where('bookings.created_at', '>=', $startDate)
                ->groupBy('users.nationality')
                ->selectRaw('users.nationality, COUNT(*) as count')
                ->get(),

            'by_language' => Booking::where('created_at', '>=', $startDate)
                ->groupBy('guest_language')
                ->selectRaw('guest_language, COUNT(*) as count')
                ->get(),

            'by_age_group' => $this->calculateAgeGroups($startDate)
        ];
    }

    private function getSeasonalTrends(): array
    {
        // Analyse des tendances saisonnières pour optimisation prix
        return Booking::selectRaw('
                MONTH(check_in) as month,
                COUNT(*) as bookings,
                AVG(total_amount) as avg_price,
                AVG(DATEDIFF(check_out, check_in)) as avg_stay_duration
            ')
            ->where('status', 'completed')
            ->where('created_at', '>=', now()->subYear())
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->toArray();
    }
}
```

---

## 🚀 **Performance et optimisation**

### **Cache strategy avancée**

```php
class CacheStrategy
{
    // Cache des recherches populaires
    public function cachePopularSearches(): void
    {
        $popularSearches = [
            ['city' => 'Abidjan', 'district' => 'Cocody'],
            ['city' => 'Abidjan', 'district' => 'Plateau'],
            ['city' => 'Abidjan', 'district' => 'Marcory'],
        ];

        foreach ($popularSearches as $search) {
            $cacheKey = 'search:' . md5(json_encode($search));

            if (!Cache::has($cacheKey)) {
                $results = app(PropertySearchService::class)->search(new SearchRequest($search));
                Cache::put($cacheKey, $results, now()->addHours(6));
            }
        }
    }

    // Cache des données de propriétés
    public function cachePropertyDetails(int $propertyId): void
    {
        $cacheKey = "property:{$propertyId}:details";

        Cache::remember($cacheKey, now()->addHours(12), function() use ($propertyId) {
            return Property::with([
                'amenities',
                'photos',
                'host',
                'reviews' => function($query) {
                    $query->where('is_public', true)
                        ->orderBy('created_at', 'desc')
                        ->limit(10);
                }
            ])->find($propertyId);
        });
    }
}
```

### **Optimisation base de données**

```sql
-- Index composites pour recherches fréquentes
CREATE INDEX idx_search_location_dates ON properties (city, district, status);
CREATE INDEX idx_booking_property_status ON bookings (property_id, status, check_in, check_out);
CREATE INDEX idx_availability_search ON bookings (property_id, check_in, check_out, status);

-- Index pour analytics
CREATE INDEX idx_bookings_analytics ON bookings (created_at, status, total_amount);
CREATE INDEX idx_revenue_analysis ON payments (created_at, status, amount);

-- Vues matérialisées pour reporting
CREATE VIEW property_performance AS
SELECT
    p.id,
    p.title,
    COUNT(b.id) as total_bookings,
    SUM(b.total_amount) as total_revenue,
    AVG(r.overall_rating) as avg_rating,
    COUNT(r.id) as review_count
FROM properties p
LEFT JOIN bookings b ON p.id = b.property_id AND b.status = 'completed'
LEFT JOIN reviews r ON b.id = r.booking_id AND r.is_public = true
GROUP BY p.id;
```

---

## 🔄 **Intégrations externes**

### **API de géolocalisation**

```php
class GeolocationService
{
    public function enrichPropertyLocation(Property $property): void
    {
        if (!$property->latitude || !$property->longitude) {
            $coordinates = $this->geocodeAddress($property->address, $property->city);

            $property->update([
                'latitude' => $coordinates['lat'],
                'longitude' => $coordinates['lng']
            ]);
        }

        // Enrichir avec points d'intérêt
        $nearbyPOIs = $this->getNearbyPointsOfInterest($property->latitude, $property->longitude);

        // Stocker dans une table séparée
        foreach ($nearbyPOIs as $poi) {
            PropertyNearbyPOI::updateOrCreate([
                'property_id' => $property->id,
                'poi_type' => $poi['type'],
                'name' => $poi['name']
            ], [
                'distance' => $poi['distance'],
                'walking_time' => $poi['walking_time']
            ]);
        }
    }
}
```

### **Service de communication omnicanal**

```php
class CommunicationService
{
    public function sendMessage(User $user, string $message, array $channels = ['email']): void
    {
        $language = $user->preferred_language ?? 'fr';

        if (in_array('email', $channels)) {
            $this->sendEmail($user, $message, $language);
        }

        if (in_array('sms', $channels) && $user->phone) {
            $this->sendSMS($user, $message, $language);
        }

        if (in_array('push', $channels)) {
            $this->sendPushNotification($user, $message, $language);
        }

        if (in_array('whatsapp', $channels) && $user->phone) {
            $this->sendWhatsApp($user, $message, $language);
        }
    }
}
```

---

## 📱 **API REST Documentation**

### **Endpoints principaux**

#### **Authentification**

```http
POST /api/auth/register
POST /api/auth/login
POST /api/auth/logout
POST /api/auth/refresh
POST /api/auth/verify-email
POST /api/auth/verify-phone
```

#### **Recherche et propriétés**

```http
GET /api/properties/search
GET /api/properties/{id}
GET /api/properties/{id}/availability
GET /api/properties/{id}/reviews
POST /api/properties (host only)
PUT /api/properties/{id} (host only)
```

#### **Réservations**

```http
POST /api/bookings
GET /api/bookings (user's bookings)
GET /api/bookings/{id}
PUT /api/bookings/{id}/cancel
POST /api/bookings/{id}/review
```

#### **Paiements**

```http
POST /api/payments/initialize
POST /api/payments/confirm
GET /api/payments/{id}/status
```

### **Format de réponse standardisé**

```json
{
    "success": true,
    "data": {
        // Données de la réponse
    },
    "meta": {
        "current_page": 1,
        "per_page": 20,
        "total": 150,
        "language": "fr"
    },
    "message": "Operation successful"
}
```

---

_Documentation technique v1.0 - Plateforme Jatsmanor de réservation/location_
