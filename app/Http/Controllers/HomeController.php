<?php

namespace App\Http\Controllers;

use App\Models\Residence;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class HomeController extends Controller
{
    public function index()
    {
        // Résidences en vedette - prioriser les résidences avec images
        $featuredResidences = Residence::featured()
            ->with(['images' => function ($query) {
                $query->where('is_primary', true);
            }])
            ->orderBy('is_active', 'desc')
            ->orderBy('updated_at', 'desc')
            ->limit(6)
            ->get();

        // Statistiques pour la page d'accueil
        $stats = [
            'total_residences' => Residence::active()->count(),
            'happy_clients' => 150, // Vous pouvez calculer cela à partir des bookings
            'years_experience' => 10,
            'cities' => Residence::distinct('location')->count('location'),
        ];

        // Témoignages récents (avec vérification si des reviews existent)
        $testimonials = collect();
        
        try {
            if (Schema::hasTable('reviews')) {
                $testimonials = Review::approved()
                    ->with(['user', 'residence'])
                    ->where('rating', '>=', 4)
                    ->latest()
                    ->limit(3)
                    ->get();
            }
        } catch (\Exception $e) {
            // En cas d'erreur, continuer avec collection vide
        }

        // Si pas de reviews en base, utiliser des témoignages de démonstration
        if ($testimonials->isEmpty()) {
            $testimonials = collect([
                (object)[
                    'comment' => trans('messages.home.testimonial_1'),
                    'rating' => 5,
                    'user' => (object)['name' => trans('messages.home.testimonial_1_author')],
                    'residence' => (object)['location' => trans('messages.home.testimonial_1_location')],
                ],
                (object)[
                    'comment' => trans('messages.home.testimonial_2'),
                    'rating' => 5,
                    'user' => (object)['name' => trans('messages.home.testimonial_2_author')], 
                    'residence' => (object)['location' => trans('messages.home.testimonial_2_location')],
                ],
                (object)[
                    'comment' => trans('messages.home.testimonial_3'),
                    'rating' => 5,
                    'user' => (object)['name' => trans('messages.home.testimonial_3_author')],
                    'residence' => (object)['location' => trans('messages.home.testimonial_3_location')],
                ]
            ]);
        }

        // Données de compatibilité pour les anciennes vues
        $chambres = $featuredResidences->map(function ($residence) {
            return (object)[
                'id' => $residence->id,
                'slug' => $residence->slug,
                'name' => $residence->name,
                'nom' => $residence->name,
                'description' => $residence->description,
                'short_description' => $residence->short_description,
                'prix' => $residence->price,
                'image' => $residence->image,
                'is_active' => $residence->is_active,
                'price_per_night' => $residence->price_per_night,
                // Add translation fields
                'name_en' => $residence->name_en,
                'description_en' => $residence->description_en,
                'short_description_en' => $residence->short_description_en,
            ];
        });

        $avis = $testimonials->map(function ($review) {
            // Gérer les cas où $review est un modèle Eloquent ou un objet simple
            if (isset($review->comment)) {
                // Objet de démonstration
                return (object)[
                    'commentaire' => $review->comment,
                    'nom' => $review->user->name,
                    'ville' => $review->residence->location,
                ];
            } else {
                // Modèle Eloquent Review
                return (object)[
                    'commentaire' => $review->comment ?? 'Excellent séjour !',
                    'nom' => $review->user->name ?? 'Client satisfait',
                    'ville' => $review->residence->location ?? 'Abidjan',
                ];
            }
        });

        return view('welcome', compact('featuredResidences', 'stats', 'testimonials', 'chambres', 'avis'));
    }

    public function galerie()
    {
        // Récupérer toutes les résidences disponibles avec leurs images
        $residences = \App\Models\Residence::where('availability_status', 'available')
            ->with(['images' => function ($query) {
                $query->orderBy('order');
            }])
            ->get();

        // Mapping des locations vers les clés de traduction
        $locationToKey = [
            'Cocody' => 'category_cocody',
            'Cocody, Abidjan' => 'category_cocody_abidjan',
            'Marcory, Abidjan' => 'category_marcory_abidjan',
            'Plateau, Abidjan' => 'category_plateau_abidjan',
            'Yopougon' => 'category_yopougon',
            'Yoppongon' => 'category_yoppongon',
        ];

        $galleryItems = collect();
        $categories = collect();

        foreach ($residences as $residence) {
            // Utiliser la clé de traduction pour la catégorie
            $categoryKey = $locationToKey[$residence->location] ?? \Illuminate\Support\Str::slug($residence->location, '_');
            // Remove any accidental double 'category_' prefix
            if (strpos($categoryKey, 'category_category_') === 0) {
                $categoryKey = substr($categoryKey, 16); // remove 'category_category_'
            }
            // Remove any accidental single 'category_' prefix if double was not found
            elseif (strpos($categoryKey, 'category_') === 0 && substr_count($categoryKey, 'category_') > 1) {
                $categoryKey = preg_replace('/^category_+/', 'category_', $categoryKey);
            }
            $categories->push($categoryKey);

            // Images de la nouvelle structure
            foreach ($residence->images as $image) {
                $galleryItems->push([
                    'id' => $image->id,
                    'image_path' => $image->image_path,
                    'title' => $residence->name,
                    'description' => $image->description ?? $residence->description,
                    'category' => $categoryKey,
                    'residence_id' => $residence->id,
                    'is_primary' => $image->is_primary,
                    'order' => $image->order,
                    'residence' => $residence
                ]);
            }

            // Fallback pour les anciennes images (si la colonne images existe)
            if ($residence->images->isEmpty() && isset($residence->images_json)) {
                $images = json_decode($residence->images_json, true);
                if (is_array($images)) {
                    foreach ($images as $index => $imagePath) {
                        $galleryItems->push([
                            'id' => 'legacy_' . $residence->id . '_' . $index,
                            'image_path' => $imagePath,
                            'title' => $residence->name,
                            'description' => $residence->description,
                            'category' => $categoryKey,
                            'residence_id' => $residence->id,
                            'is_primary' => $index === 0,
                            'order' => $index,
                            'residence' => $residence
                        ]);
                    }
                }
            }

            // Si pas d'images dans images et pas d'ancien système, utiliser l'image principale
            if ($residence->images->isEmpty() && !isset($residence->images_json) && $residence->image) {
                $galleryItems->push([
                    'id' => 'main_' . $residence->id,
                    'image_path' => $residence->image,
                    'title' => $residence->name,
                    'description' => $residence->description,
                    'category' => $categoryKey,
                    'residence_id' => $residence->id,
                    'is_primary' => true,
                    'order' => 0,
                    'residence' => $residence
                ]);
            }
        }

        // Trier par ordre et images principales en premier
        $galleryItems = $galleryItems->sortBy([
            ['is_primary', 'desc'],
            ['order', 'asc']
        ]);

        // Obtenir les catégories uniques
        $categories = $categories->unique()->sort()->values();

        return view('galerie', [
            'galleryItems' => $galleryItems,
            'categories' => $categories,
            'pageTitle' => 'Galerie Jatsmanor',
            'subtitle' => 'Découvrez nos magnifiques résidences et leurs aménagements'
        ]);
    }

    public function services()
    {
        return view('services');
    }
}
