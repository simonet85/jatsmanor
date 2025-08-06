<?php

namespace Database\Seeders;

use App\Models\Residence;
use App\Models\ResidenceImage;
use App\Models\Amenity;
use Illuminate\Database\Seeder;

class ResidenceSeeder extends Seeder
{
    public function run(): void
    {
        $residences = [
            [
                'name' => 'Villa Deluxe Cocody',
                'description' => 'Magnifique villa moderne située dans le quartier résidentiel de Cocody. Cette propriété exceptionnelle offre un cadre de vie luxueux avec une architecture contemporaine et des finitions haut de gamme. Parfaite pour les séjours d\'affaires ou de loisirs.',
                'short_description' => 'Villa moderne et luxueuse à Cocody avec piscine privée',
                'price' => 150000,
                'price_per_night' => 150000,
                'location' => 'Cocody, Abidjan',
                'floor' => 'Villa 2 étages',
                'size' => 250,
                'surface' => 250,
                'max_guests' => 6,
                'rating' => 4.8,
                'availability_status' => 'available',
                'image' => '/images/residences/villa-cocody-1.jpg',
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Appartement Standing Plateau',
                'description' => 'Appartement de standing situé au cœur du plateau, le quartier d\'affaires d\'Abidjan. Idéalement placé pour les professionnels, cet appartement moderne offre tout le confort nécessaire avec une vue imprenable sur la lagune.',
                'short_description' => 'Appartement moderne au Plateau avec vue sur lagune',
                'price' => 85000,
                'price_per_night' => 85000,
                'location' => 'Plateau, Abidjan',
                'floor' => '12ème étage',
                'size' => 120,
                'surface' => 120,
                'max_guests' => 4,
                'rating' => 4.5,
                'availability_status' => 'available',
                'image' => '/images/residences/appart-plateau-1.jpg',
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Maison Familiale Marcory',
                'description' => 'Charmante maison familiale dans le quartier calme de Marcory. Idéale pour les familles, elle dispose d\'un grand jardin et d\'espaces de vie confortables. Proche des écoles internationales et des centres commerciaux.',
                'short_description' => 'Maison familiale avec jardin à Marcory',
                'price' => 75000,
                'price_per_night' => 75000,
                'location' => 'Marcory, Abidjan',
                'floor' => 'Rez-de-chaussée + 1 étage',
                'size' => 180,
                'surface' => 180,
                'max_guests' => 5,
                'rating' => 4.2,
                'availability_status' => 'available',
                'image' => '/images/residences/maison-marcory-1.jpg',
                'is_featured' => false,
                'is_active' => true,
            ],
        ];

        foreach ($residences as $residenceData) {
            $residence = Residence::firstOrCreate(
                ['name' => $residenceData['name']],
                $residenceData
            );

            // Only add images, rooms, and amenities if this is a new residence
            if ($residence->wasRecentlyCreated) {
                // Add some images for each residence
                $images = [
                    [
                        'residence_id' => $residence->id,
                        'image_path' => $residenceData['image'],
                        'alt_text' => 'Image principale de ' . $residence->name,
                        'is_primary' => true,
                        'order' => 1,
                    ],
                    [
                        'residence_id' => $residence->id,
                        'image_path' => str_replace('-1.jpg', '-2.jpg', $residenceData['image']),
                        'alt_text' => 'Salon de ' . $residence->name,
                        'is_primary' => false,
                        'order' => 2,
                    ],
                    [
                        'residence_id' => $residence->id,
                        'image_path' => str_replace('-1.jpg', '-3.jpg', $residenceData['image']),
                        'alt_text' => 'Chambre de ' . $residence->name,
                        'is_primary' => false,
                        'order' => 3,
                    ],
                ];

                foreach ($images as $imageData) {
                    ResidenceImage::create($imageData);
                }

                // Add some random amenities
                $amenityIds = Amenity::pluck('id')->random(rand(5, 10));
                $residence->amenities()->attach($amenityIds);
            }
        }
    }
}
