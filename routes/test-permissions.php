<?php

use Illuminate\Support\Facades\Route;

// Route temporaire pour tester les permissions
Route::get('/test-permissions', function () {
    if (!auth()->check()) {
        return 'Utilisateur non connecté';
    }
    
    $user = auth()->user();
    $roles = $user->getRoleNames();
    $permissions = $user->getAllPermissions()->pluck('name');
    
    return [
        'user' => $user->name,
        'email' => $user->email,
        'roles' => $roles,
        'permissions' => $permissions,
        'has_manage_contacts' => $user->can('manage-contacts'),
        'has_view_notifications' => $user->can('view-contact-notifications'),
    ];
})->middleware('auth');
