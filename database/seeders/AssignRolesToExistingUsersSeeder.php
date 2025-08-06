<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AssignRolesToExistingUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Assurez-vous que les rôles existent
        $adminRole = Role::firstOrCreate(['name' => 'Administrator']);
        $clientRole = Role::firstOrCreate(['name' => 'Client']);

        // Récupérer tous les utilisateurs sans rôles
        $usersWithoutRoles = User::doesntHave('roles')->get();

        foreach ($usersWithoutRoles as $user) {
            // Le premier utilisateur devient admin, les autres deviennent clients
            if ($user->id === 1 || $user->email === 'admin@residences.com') {
                $user->assignRole('Administrator');
                $this->command->info("Assigned Administrator role to user: {$user->name} ({$user->email})");
            } else {
                $user->assignRole('Client');
                $this->command->info("Assigned Client role to user: {$user->name} ({$user->email})");
            }

            // S'assurer que tous les utilisateurs sont actifs par défaut
            if (!isset($user->is_active)) {
                $user->update(['is_active' => true]);
            }
        }

        $this->command->info('Roles assignment completed!');
    }
}
