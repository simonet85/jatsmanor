<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run roles and permissions seeder first
        $this->call([
            RolesAndPermissionsSeeder::class,
        ]);

        // Create Administrator test user
        $admin = User::firstOrCreate(
            ['email' => 'admin@residences.com'],
            [
                'name' => 'Administrateur',
                'password' => Hash::make('admin123'),
                'phone' => '+225 01 02 03 04 05',
                'is_active' => true
            ]
        );
        $admin->assignRole('Administrator');

        // Create Client test user
        $client = User::firstOrCreate(
            ['email' => 'client@residences.com'],
            [
                'name' => 'Client Test',
                'password' => Hash::make('client123'),
                'phone' => '+225 07 08 09 10 11',
                'is_active' => true
            ]
        );
        $client->assignRole('Client');

        // Create additional test clients
        $testClients = User::factory(3)->create();
        foreach ($testClients as $testClient) {
            $testClient->assignRole('Client');
        }

        // Run our custom seeders
        $this->call([
            AmenitySeeder::class,
            ResidenceSeeder::class,
        ]);
    }
}
