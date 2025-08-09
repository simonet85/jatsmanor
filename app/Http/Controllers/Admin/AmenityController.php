<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AmenityController extends Controller
{
    public function index()
    {
        $amenities = Amenity::orderBy('name')->get();
        
        // If it's an AJAX request, return JSON
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'amenities' => $amenities
            ]);
        }
        
        return view('admin.amenities.index', compact('amenities'));
    }

    public function ajaxIndex()
    {
        $amenities = Amenity::orderBy('name')->get();
        
        return response()->json([
            'success' => true,
            'amenities' => $amenities
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:amenities,name',
            'icon' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $amenity = Amenity::create([
                'name' => $request->name,
                'icon' => $request->icon ?? 'fas fa-check',
                'description' => $request->description,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Équipement créé avec succès',
                'amenity' => $amenity
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de l\'équipement'
            ], 500);
        }
    }

    public function update(Request $request, Amenity $amenity)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:amenities,name,' . $amenity->id,
            'icon' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $amenity->update([
                'name' => $request->name,
                'icon' => $request->icon ?? 'fas fa-check',
                'description' => $request->description,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Équipement mis à jour avec succès',
                'amenity' => $amenity
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour de l\'équipement'
            ], 500);
        }
    }

    public function destroy(Amenity $amenity)
    {
        try {
            // Check if amenity is being used by any residences
            $residencesCount = $amenity->residences()->count();
            
            if ($residencesCount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Impossible de supprimer cet équipement car il est utilisé par ' . $residencesCount . ' résidence(s)'
                ], 422);
            }

            $amenity->delete();

            return response()->json([
                'success' => true,
                'message' => 'Équipement supprimé avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression de l\'équipement'
            ], 500);
        }
    }
}
