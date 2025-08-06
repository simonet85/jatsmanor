<?php

use Spatie\Permission\Models\Role;
use App\Models\User;

// Créer le rôle admin s'il n'existe pas
$adminRole = Role::firstOrCreate(['name' => 'admin']);
echo 'Rôle admin créé/trouvé: ' . $adminRole->name . PHP_EOL;

// Lister tous les utilisateurs pour voir les emails disponibles
echo "\nUtilisateurs disponibles:" . PHP_EOL;
$users = User::all();
foreach ($users as $user) {
    echo "- ID: {$user->id}, Email: {$user->email}, Nom: {$user->name}" . PHP_EOL;
}

// Trouver l'utilisateur admin et lui assigner le rôle
$adminUser = User::find(1); // Prendre le premier utilisateur
if ($adminUser) {
    $adminUser->assignRole('admin');
    echo "\nRôle admin assigné à: {$adminUser->email}" . PHP_EOL;
    
    // Vérifier les rôles de l'utilisateur
    echo "Rôles de l'utilisateur: " . $adminUser->getRoleNames()->implode(', ') . PHP_EOL;
} else {
    echo 'Aucun utilisateur trouvé' . PHP_EOL;
}
