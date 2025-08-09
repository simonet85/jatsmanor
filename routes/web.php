<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResidenceController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\Admin\ContactAdminController;
use App\Http\Controllers\ResidenceSearchController;
use App\Http\Controllers\LanguageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Routes pour la gestion des langues
Route::prefix('language')->name('language.')->group(function () {
    Route::post('/switch/{locale}', [LanguageController::class, 'switch'])->name('switch');
    Route::get('/current', [LanguageController::class, 'current'])->name('current');
    Route::get('/translations/{locale?}', [LanguageController::class, 'translations'])->name('translations');
});

// Route de test pour la localisation (à supprimer en production)
Route::get('/test-localization', function () {
    return view('test-localization');
})->name('test.localization');

// Route de debug pour la localisation (à supprimer en production)
Route::get('/debug-localization', function () {
    return view('debug-localization');
})->name('debug.localization')->middleware('localization');

// Routes publiques avec middleware de localisation
Route::middleware('localization')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/residences', [ResidenceController::class, 'index'])->name('residences');
    Route::get('/residences/search', [ResidenceSearchController::class, 'search'])->name('residences.search');
    Route::get('/residence/{residence}', [ResidenceController::class, 'show'])->name('residence.details');
    Route::get('/residence-details/{residence?}', [ResidenceController::class, 'details'])->name('residence.details.page');
    Route::get('/galerie', [HomeController::class, 'galerie'])->name('galerie');
    Route::get('/galerie/{residence}', [GalleryController::class, 'show'])->name('galerie.show');
    Route::get('/services', [HomeController::class, 'services'])->name('services');

    // Routes de contact et newsletter
    Route::get('/contact', [ContactController::class, 'index'])->name('contact');
    Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
    Route::post('/newsletter', [ContactController::class, 'newsletter'])->name('newsletter.subscribe');
    Route::get('/newsletter/unsubscribe/{token}', [ContactController::class, 'unsubscribe'])->name('newsletter.unsubscribe');

    // Route pour recherche AJAX (optionnelle)
    Route::get('/api/residences/search', [ResidenceController::class, 'search'])->name('residences.search');

    // Routes légales manquantes (à créer si nécessaire)
    Route::view('/terms', 'legal.terms')->name('terms');
    Route::view('/privacy', 'legal.privacy')->name('privacy');

    // Route de connexion alternative (utilisée dans registration-form)
    Route::get('/connexion', function () {
        return redirect()->route('login');
    })->name('connexion');
});

// Routes de réservation (authentification requise)
Route::middleware('auth')->group(function () {
    Route::get('/residence/{residence}/booking', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/residence/{residence}/booking', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/booking/{booking}/confirmation', [BookingController::class, 'confirmation'])->name('booking.confirmation');
    Route::get('/mes-reservations', [BookingController::class, 'index'])->name('booking.index');
    Route::get('/booking/{booking}', [BookingController::class, 'show'])->name('booking.show');
    Route::patch('/booking/{booking}/cancel', [BookingController::class, 'cancel'])->name('booking.cancel');
    
    // Actions rapides pour les réservations
    Route::post('/booking/{booking}/send-confirmation', [BookingController::class, 'sendConfirmation'])->name('booking.send-confirmation');
    Route::post('/booking/{booking}/generate-invoice', [BookingController::class, 'generateInvoice'])->name('booking.generate-invoice');
});



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Test translations interface
Route::get('/test-translations', function () {
    return view('dashboard.test-translations');
})->name('test-translations');

// Routes utilisateur authentifié
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard/residences', [DashboardController::class, 'residences'])->name('dashboard.residences')->middleware('role:Administrator');
    Route::get('/dashboard/chambres', [DashboardController::class, 'chambres'])->name('dashboard.chambres')->middleware('role:Administrator');
    Route::get('/dashboard/reservations', [DashboardController::class, 'reservations'])->name('dashboard.reservations')->middleware('role:Administrator');
    Route::get('/dashboard/utilisateurs', [DashboardController::class, 'utilisateurs'])->name('dashboard.utilisateurs')->middleware('role:Administrator');
    Route::get('/dashboard/amenities', [DashboardController::class, 'amenities'])->name('dashboard.amenities')->middleware('role:Administrator');
    Route::get('/dashboard/parametres', [DashboardController::class, 'parametres'])->name('dashboard.parametres')->middleware('role:Administrator');
    
    // Routes pour les paramètres (Administrateurs seulement)
    Route::prefix('admin/settings')->name('admin.settings.')->middleware('role:Administrator')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\SettingController::class, 'index'])->name('index');
        Route::post('/general', [App\Http\Controllers\Admin\SettingController::class, 'updateGeneral'])->name('update-general');
        Route::post('/contact', [App\Http\Controllers\Admin\SettingController::class, 'updateContact'])->name('update-contact');
        Route::post('/booking', [App\Http\Controllers\Admin\SettingController::class, 'updateBooking'])->name('update-booking');
        Route::post('/language', [App\Http\Controllers\Admin\SettingController::class, 'updateLanguage'])->name('update-language');
        Route::post('/frontend', [App\Http\Controllers\Admin\SettingController::class, 'updateFrontend'])->name('update-frontend');
    });
    Route::get('/dashboard/mon-compte', [App\Http\Controllers\ProfileController::class, 'show'])->name('dashboard.mon-compte');
    Route::post('/dashboard/mon-compte/profile', [App\Http\Controllers\ProfileController::class, 'updateProfile'])->name('profile.update.dashboard');
    Route::post('/dashboard/mon-compte/password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::post('/dashboard/mon-compte/avatar', [App\Http\Controllers\ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');

    // Routes CRUD pour la gestion des résidences (Administrateurs seulement)
    Route::prefix('admin/residences')->name('admin.residences.')->middleware('role:Administrator')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\ResidenceManagementController::class, 'index'])->name('index');
        Route::post('/', [App\Http\Controllers\Admin\ResidenceManagementController::class, 'store'])->name('store');
        Route::get('/{residence}/edit', [App\Http\Controllers\Admin\ResidenceManagementController::class, 'edit'])->name('edit');
        Route::put('/{residence}', [App\Http\Controllers\Admin\ResidenceManagementController::class, 'update'])->name('update');
        Route::patch('/{residence}/toggle-status', [App\Http\Controllers\Admin\ResidenceManagementController::class, 'toggleStatus'])->name('toggle-status');
        Route::delete('/{residence}', [App\Http\Controllers\Admin\ResidenceManagementController::class, 'destroy'])->name('destroy');
        
        // Routes pour la gestion des images
        Route::post('/{residence}/images', [App\Http\Controllers\Admin\ResidenceManagementController::class, 'uploadImages'])->name('images.upload');
        Route::delete('/{residence}/images/{image}', [App\Http\Controllers\Admin\ResidenceManagementController::class, 'deleteImage'])->name('images.delete');
        Route::patch('/{residence}/images/{image}/primary', [App\Http\Controllers\Admin\ResidenceManagementController::class, 'setPrimaryImage'])->name('images.set-primary');
        Route::patch('/{residence}/images/reorder', [App\Http\Controllers\Admin\ResidenceManagementController::class, 'reorderImages'])->name('images.reorder');
    });

    // Routes CRUD pour la gestion des équipements (Administrateurs seulement)
    Route::prefix('admin/amenities')->name('admin.amenities.')->middleware('role:Administrator')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\AmenityController::class, 'index'])->name('index');
        Route::get('/ajax', [App\Http\Controllers\Admin\AmenityController::class, 'ajaxIndex'])->name('ajax.index');
        Route::post('/', [App\Http\Controllers\Admin\AmenityController::class, 'store'])->name('store');
        Route::put('/{amenity}', [App\Http\Controllers\Admin\AmenityController::class, 'update'])->name('update');
        Route::delete('/{amenity}', [App\Http\Controllers\Admin\AmenityController::class, 'destroy'])->name('destroy');
    });

    // Routes CRUD pour la gestion des réservations (Administrateurs seulement)
    Route::prefix('admin/bookings')->name('admin.bookings.')->middleware('role:Administrator')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\BookingManagementController::class, 'index'])->name('index');
        Route::post('/', [App\Http\Controllers\Admin\BookingManagementController::class, 'index'])->name('filter'); // Pour AJAX
        Route::get('/{booking}', [App\Http\Controllers\Admin\BookingManagementController::class, 'show'])->name('show');
        Route::patch('/{booking}/status', [App\Http\Controllers\Admin\BookingManagementController::class, 'updateStatus'])->name('update-status');
        Route::delete('/{booking}', [App\Http\Controllers\Admin\BookingManagementController::class, 'destroy'])->name('destroy');
        Route::patch('/{id}/restore', [App\Http\Controllers\Admin\BookingManagementController::class, 'restore'])->name('restore');
        Route::delete('/{id}/force-delete', [App\Http\Controllers\Admin\BookingManagementController::class, 'forceDelete'])->name('force-delete');
        Route::get('/export/data', [App\Http\Controllers\Admin\BookingManagementController::class, 'export'])->name('export');
    });

    // Routes for Booking Management (Administrators only)
    Route::prefix('admin/bookings')->name('admin.bookings.')->middleware(['role:Administrator', 'permission:view-bookings'])->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\BookingAdminController::class, 'index'])->name('index');
        Route::get('/stats', [App\Http\Controllers\Admin\BookingAdminController::class, 'getStats'])->name('stats');
        Route::get('/{booking}', [App\Http\Controllers\Admin\BookingAdminController::class, 'show'])->name('show');
        Route::patch('/{booking}/status', [App\Http\Controllers\Admin\BookingAdminController::class, 'updateStatus'])->name('update-status');
        Route::patch('/{booking}/payment', [App\Http\Controllers\Admin\BookingAdminController::class, 'updatePaymentStatus'])->name('update-payment');
        Route::post('/bulk', [App\Http\Controllers\Admin\BookingAdminController::class, 'bulkAction'])->name('bulk');
    });

    // Routes for Contact Message Management (Administrators only)
    Route::prefix('admin/contact')->name('admin.contact.')->middleware(['role:Administrator', 'permission:manage-contacts'])->group(function () {
        Route::get('/', [ContactAdminController::class, 'index'])->name('index');
        Route::patch('/{message}/status', [ContactAdminController::class, 'updateStatus'])->name('updateStatus');
        Route::post('/bulk', [ContactAdminController::class, 'bulkAction'])->name('bulk');
        Route::get('/{message}/details', [ContactAdminController::class, 'show'])->name('show');
    });

    // Routes CRUD pour la gestion des utilisateurs (Administrateurs seulement)
    Route::prefix('admin/users')->name('admin.users.')->middleware('role:Administrator')->group(function () {
        Route::get('/', [App\Http\Controllers\UserManagementController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\UserManagementController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\UserManagementController::class, 'store'])->name('store');
        Route::get('/{user}', [App\Http\Controllers\UserManagementController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [App\Http\Controllers\UserManagementController::class, 'edit'])->name('edit');
        Route::put('/{user}', [App\Http\Controllers\UserManagementController::class, 'update'])->name('update');
        Route::delete('/{user}', [App\Http\Controllers\UserManagementController::class, 'destroy'])->name('destroy');
        Route::patch('/{user}/toggle-status', [App\Http\Controllers\UserManagementController::class, 'toggleStatus'])->name('toggle-status');
        Route::patch('/{user}/role', [App\Http\Controllers\UserManagementController::class, 'updateRole'])->name('update-role');
    });

    // Routes pour les clients (leurs propres réservations)
    Route::prefix('client')->name('client.')->middleware('role:Client')->group(function () {
        Route::get('/reservations', [App\Http\Controllers\Client\ClientBookingController::class, 'index'])->name('bookings.index');
        Route::get('/reservations/{booking}', [App\Http\Controllers\Client\ClientBookingController::class, 'show'])->name('bookings.show');
        Route::patch('/reservations/{booking}/cancel', [App\Http\Controllers\Client\ClientBookingController::class, 'cancel'])->name('bookings.cancel');
    });
});

require __DIR__.'/auth.php';

// Route de test pour les dates
Route::get('/test-dates', function() {
    $date = now();
    return [
        'timezone_config' => config('app.timezone'),
        'now_local' => $date->format('d/m/Y H:i:s'),
        'now_utc' => $date->utc()->format('d/m/Y H:i:s') . ' UTC',
        'format_local_datetime' => format_local_datetime($date),
        'format_local_date' => format_local_date($date),
        'booking_sample' => \App\Models\Booking::with('user', 'residence')->first() ? [
            'id' => \App\Models\Booking::first()->id,
            'check_in_raw' => \App\Models\Booking::first()->check_in->toISOString(),
            'check_in_formatted' => format_local_datetime(\App\Models\Booking::first()->check_in),
            'created_at_formatted' => format_local_date(\App\Models\Booking::first()->created_at)
        ] : 'No booking found'
    ];
});

// Routes de test pour les pages d'erreur (développement uniquement)
if (app()->environment('local')) {
    Route::get('/test-404', function() {
        abort(404);
    });
    
    Route::get('/test-403', function() {
        abort(403);
    });
    
    Route::get('/test-500', function() {
        abort(500);
    });
}
