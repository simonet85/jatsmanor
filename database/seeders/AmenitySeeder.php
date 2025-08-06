<?php

namespace Database\Seeders;

use App\Models\Amenity;
use Illuminate\Database\Seeder;

class AmenitySeeder extends Seeder
{
    public function run(): void
    {
        $amenities = [
            // Comfort & Convenience
            ['name' => 'Wi-Fi gratuit', 'icon' => 'wifi', 'category' => 'service'],
            ['name' => 'Climatisation', 'icon' => 'snowflake', 'category' => 'room'],
            ['name' => 'Chauffage', 'icon' => 'fire', 'category' => 'room'],
            ['name' => 'Télévision', 'icon' => 'tv', 'category' => 'room'],
            ['name' => 'Cuisine équipée', 'icon' => 'utensils', 'category' => 'room'],
            ['name' => 'Réfrigérateur', 'icon' => 'cube', 'category' => 'room'],
            ['name' => 'Micro-ondes', 'icon' => 'square', 'category' => 'room'],
            ['name' => 'Lave-linge', 'icon' => 'tshirt', 'category' => 'service'],
            ['name' => 'Sèche-linge', 'icon' => 'wind', 'category' => 'service'],
            ['name' => 'Fer à repasser', 'icon' => 'shirt', 'category' => 'service'],

            // Safety & Security
            ['name' => 'Détecteur de fumée', 'icon' => 'shield-check', 'category' => 'building'],
            ['name' => 'Extincteur', 'icon' => 'fire-extinguisher', 'category' => 'building'],
            ['name' => 'Trousse de premiers secours', 'icon' => 'first-aid', 'category' => 'service'],
            ['name' => 'Coffre-fort', 'icon' => 'lock', 'category' => 'room'],
            ['name' => 'Caméras de sécurité', 'icon' => 'camera', 'category' => 'building'],

            // Outdoor & Recreation
            ['name' => 'Piscine', 'icon' => 'swimmer', 'category' => 'building'],
            ['name' => 'Jardin', 'icon' => 'leaf', 'category' => 'building'],
            ['name' => 'Terrasse', 'icon' => 'home', 'category' => 'building'],
            ['name' => 'Balcon', 'icon' => 'building', 'category' => 'room'],
            ['name' => 'Barbecue', 'icon' => 'fire', 'category' => 'building'],
            ['name' => 'Parking gratuit', 'icon' => 'car', 'category' => 'building'],
            ['name' => 'Garage', 'icon' => 'warehouse', 'category' => 'building'],

            // Services
            ['name' => 'Ménage inclus', 'icon' => 'broom', 'category' => 'service'],
            ['name' => 'Service de conciergerie', 'icon' => 'concierge-bell', 'category' => 'service'],
            ['name' => 'Petit déjeuner', 'icon' => 'coffee', 'category' => 'service'],
            ['name' => 'Animaux acceptés', 'icon' => 'paw', 'category' => 'service'],
        ];

        foreach ($amenities as $amenity) {
            Amenity::firstOrCreate(
                ['name' => $amenity['name']],
                [
                    'icon' => $amenity['icon'],
                    'category' => $amenity['category'],
                ]
            );
        }
    }
}
