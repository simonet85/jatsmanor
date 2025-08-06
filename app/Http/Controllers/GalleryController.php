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

        // Préparer les données pour la galerie
        $galleryItems = collect();
        $categories = collect();

        foreach ($residences as $residence) {
            // Ajouter la catégorie (utiliser location comme catégorie pour l'instant)
            if ($residence->location) {
                $categories->push($residence->location);
            }

            // Images de la nouvelle structure
            foreach ($residence->images as $image) {
                $galleryItems->push([
                    'id' => $image->id,
                    'image_path' => $image->image_path,
                    'title' => $residence->name, // utiliser 'name' au lieu de 'title'
                    'description' => $image->description ?? $residence->description,
                    'category' => $residence->location, // utiliser location comme catégorie
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
                            'category' => $residence->location,
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
                    'category' => $residence->location,
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
