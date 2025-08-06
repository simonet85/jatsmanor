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
            if (\Schema::hasTable('reviews')) {
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
                'nom' => $residence->name,
                'description' => $residence->short_description,
                'prix' => $residence->price,
                'image' => $residence->image,
                'is_active' => $residence->is_active,
                'price_per_night' => $residence->price_per_night,
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
        return view('galerie');
    }

    public function services()
    {
        return view('services');
    }
}
