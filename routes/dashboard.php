<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BookingManagementController;

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

// Routes du dashboard
Route::middleware(['auth'])->prefix('dashboard')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/residences', [DashboardController::class, 'residences'])->name('dashboard.residences');
    Route::get('/reservations', [DashboardController::class, 'reservations'])->name('dashboard.reservations');
});

// Routes pour la gestion des réservations (admin)
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('bookings', [BookingManagementController::class, 'index'])->name('admin.bookings.index');
    Route::post('bookings', [BookingManagementController::class, 'index']); // Pour AJAX
    Route::get('bookings/{booking}', [BookingManagementController::class, 'show'])->name('admin.bookings.show');
    Route::patch('bookings/{booking}/status', [BookingManagementController::class, 'updateStatus'])->name('admin.bookings.update-status');
    Route::delete('bookings/{booking}', [BookingManagementController::class, 'destroy'])->name('admin.bookings.destroy');
    Route::patch('bookings/{booking}/restore', [BookingManagementController::class, 'restore'])->name('admin.bookings.restore');
    Route::delete('bookings/{booking}/force-delete', [BookingManagementController::class, 'forceDelete'])->name('admin.bookings.force-delete');
});
