<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Dashboard access
            'access-dashboard',
            
            // User management
            'view-users',
            'create-users',
            'edit-users',
            'delete-users',
            
            // Residence management
            'view-residences',
            'create-residences',
            'edit-residences',
            'delete-residences',
            
            // Booking management
            'view-bookings',
            'create-bookings',
            'edit-bookings',
            'delete-bookings',
            'restore-bookings',
            'force-delete-bookings',
            
            // Settings
            'manage-settings',
            
            // Reports
            'view-reports',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        
        // Administrator role - has all permissions
        $adminRole = Role::create(['name' => 'Administrator']);
        $adminRole->givePermissionTo(Permission::all());

        // Client role - limited permissions
        $clientRole = Role::create(['name' => 'Client']);
        $clientRole->givePermissionTo([
            'access-dashboard',
            'view-bookings', // Only their own bookings
            'create-bookings', // Can make new bookings
        ]);

        // Assign roles to existing users (if any)
        // You can modify this section based on your needs
        $firstUser = User::first();
        if ($firstUser) {
            $firstUser->assignRole('Administrator');
        }
    }
}
