<?php

namespace App\Http\Controllers;

use App\Models\Residence;
use App\Models\ResidenceImage;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        // Récupérer toutes les résidences disponibles avec leurs images
        $residences = Residence::where('availability_status', 'available')
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

        // Filtrer par catégorie si spécifié
        $selectedCategory = $request->get('category');
        if ($selectedCategory && $selectedCategory !== 'all') {
            $galleryItems = $galleryItems->filter(function ($item) use ($selectedCategory) {
                return strtolower($item['category']) === strtolower($selectedCategory);
            });
        }

        return view('gallery.index', [
            'galleryItems' => $galleryItems,
            'categories' => $categories,
            'selectedCategory' => $selectedCategory,
            'pageTitle' => 'Galerie Jatsmanor',
            'subtitle' => 'Découvrez nos magnifiques résidences et leurs aménagements'
        ]);
    }

    public function show($id)
    {
        $residence = Residence::with(['images' => function ($query) {
            $query->orderBy('order');
        }])->findOrFail($id);

        $images = $residence->images;

        // Fallback pour les anciennes images (si la colonne images_json existe)
        if ($images->isEmpty() && isset($residence->images_json)) {
            $legacyImages = json_decode($residence->images_json, true);
            if (is_array($legacyImages)) {
                $images = collect($legacyImages)->map(function ($imagePath, $index) use ($residence) {
                    return (object) [
                        'id' => 'legacy_' . $residence->id . '_' . $index,
                        'image_path' => $imagePath,
                        'description' => null,
                        'is_primary' => $index === 0,
                        'order' => $index
                    ];
                });
            }
        }

        // Si pas d'images dans images et pas d'ancien système, utiliser l'image principale
        if ($images->isEmpty() && $residence->image) {
            $images = collect([(object) [
                'id' => 'main_' . $residence->id,
                'image_path' => $residence->image,
                'description' => null,
                'is_primary' => true,
                'order' => 0
            ]]);
        }

        return view('gallery.show', [
            'residence' => $residence,
            'images' => $images,
            'pageTitle' => $residence->name, // utiliser 'name' au lieu de 'title'
            'subtitle' => $residence->description
        ]);
    }
}
