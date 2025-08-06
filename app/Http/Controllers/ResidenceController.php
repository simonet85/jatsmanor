<?php

namespace App\Http\Controllers;

use App\Models\Residence;
use App\Models\Amenity;
use Illuminate\Http\Request;

class ResidenceController extends Controller
{
    /**
     * Display a listing of residences.
     */
    public function index(Request $request)
    {
        $query = Residence::with(['images', 'amenities']);

        // Filtres de recherche
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->input('max_price'));
        }

        if ($request->filled('min_size')) {
            $query->where('size', '>=', $request->input('min_size'));
        }

        // Tri
        $sortBy = $request->input('sort_by', 'name');
        $sortOrder = $request->input('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        $residences = $query->active()->paginate(12);
        $amenities = Amenity::all()->groupBy('category');

        return view('residences', compact('residences', 'amenities'))->with([
            'rooms' => $residences  // Passer les résidences comme "rooms" pour compatibilité avec les partials
        ]);
    }

    /**
     * Display the specified residence.
     */
    public function show(Residence $residence)
    {
        $residence->load([
            'images' => function ($query) {
                $query->ordered();
            },
            'amenities',
            'reviews.user'
        ]);
        
        // Résidences similaires
        $similarResidences = Residence::where('id', '!=', $residence->id)
            ->where('location', 'like', "%{$residence->location}%")
            ->active()
            ->with(['images' => function ($query) {
                $query->where('is_primary', true);
            }])
            ->limit(3)
            ->get();

        return view('residences.show', compact('residence', 'similarResidences'));
    }

    /**
     * Show featured residences for homepage.
     */
    public function featured()
    {
        $featuredResidences = Residence::featured()
            ->with(['images' => function ($query) {
                $query->where('is_primary', true);
            }])
            ->limit(6)
            ->get();

        return $featuredResidences;
    }

    /**
     * Search residences (for AJAX requests)
     */
    public function search(Request $request)
    {
        $query = Residence::with(['images' => function ($q) {
            $q->where('is_primary', true);
        }]);

        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $residences = $query->active()->limit(10)->get();

        if ($request->expectsJson()) {
            return response()->json($residences);
        }

        return redirect()->route('residences', ['search' => $request->input('q')]);
    }

    /**
     * Display residence details using the residence-details.blade.php template
     */
    public function details(Residence $residence = null)
    {
        // Si aucune résidence spécifiée, prendre la première disponible
        if (!$residence) {
            $residence = Residence::active()->with(['images', 'amenities'])->first();
        }

        if (!$residence) {
            abort(404, 'Aucune résidence trouvée');
        }

        $residence->load([
            'images' => function ($query) {
                $query->ordered();
            },
            'amenities',
            'reviews.user'
        ]);
        
        // Résidences similaires (exclure la résidence actuelle)
        $similarResidences = Residence::where('id', '!=', $residence->id)
            ->where('location', 'like', "%{$residence->location}%")
            ->active()
            ->with(['images', 'amenities'])
            ->limit(3)
            ->get();

        // Si pas assez de résidences similaires par location, compléter avec d'autres
        if ($similarResidences->count() < 3) {
            $additional = Residence::where('id', '!=', $residence->id)
                ->whereNotIn('id', $similarResidences->pluck('id'))
                ->active()
                ->with(['images', 'amenities'])
                ->limit(3 - $similarResidences->count())
                ->get();
            
            $similarResidences = $similarResidences->merge($additional);
        }

        return view('residence-details', compact('residence', 'similarResidences'));
    }
}
