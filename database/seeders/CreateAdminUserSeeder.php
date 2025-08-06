<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Check if Administrator role exists
        $adminRole = Role::where('name', 'Administrator')->first();
        
        if (!$adminRole) {
            $this->command->error('Administrator role not found. Please run RolesAndPermissionsSeeder first.');
            return;
        }

        // Create admin user or update existing one
        $admin = User::updateOrCreate(
            ['email' => 'admin@residences.com'],
            [
                'name' => 'Administrateur',
                'email' => 'admin@residences.com',
                'password' => Hash::make('admin123'),
                'phone' => '+225 01 02 03 04 05',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Assign Administrator role
        if (!$admin->hasRole('Administrator')) {
            $admin->assignRole('Administrator');
            $this->command->info("Administrator role assigned to: {$admin->name} ({$admin->email})");
        } else {
            $this->command->info("Administrator user already exists: {$admin->name} ({$admin->email})");
        }

        $this->command->info('Admin user creation completed!');
        $this->command->info('Login credentials:');
        $this->command->info('Email: admin@residences.com');
        $this->command->info('Password: admin123');
    }
}
