<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Residence;
use App\Models\Amenity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ResidenceManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of residences for admin management
     */
    public function index(Request $request)
    {
        $query = Residence::with(['images' => function ($q) {
            $q->where('is_primary', true)->orWhereNull('is_primary');
        }]);

        // Recherche
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filtre par statut
        if ($request->filled('status')) {
            $status = $request->input('status');
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $residences = $query->orderBy('created_at', 'desc')->paginate(15);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('dashboard.partials.residences-table', compact('residences'))->render(),
                'pagination' => $residences->links()->render()
            ]);
        }

        return view('dashboard.residences', compact('residences'));
    }

    /**
     * Store a new residence
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:255',
            'location' => 'required|string|max:255',
            'price_per_night' => 'required|numeric|min:0',
            'size' => 'nullable|numeric|min:0',
            'max_guests' => 'required|integer|min:1',
            'floor' => 'nullable|string|max:100',
            'amenities' => 'nullable|array',
            'amenities.*' => 'exists:amenities,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'is_featured' => 'boolean'
        ], [
            'name.required' => 'Le nom de la résidence est obligatoire.',
            'description.required' => 'La description est obligatoire.',
            'location.required' => 'La localisation est obligatoire.',
            'price_per_night.required' => 'Le prix par nuit est obligatoire.',
            'price_per_night.numeric' => 'Le prix doit être un nombre valide.',
            'max_guests.required' => 'Le nombre maximum d\'invités est obligatoire.',
            'image.image' => 'Le fichier doit être une image.',
            'image.max' => 'L\'image ne doit pas dépasser 2MB.'
        ]);

        // Traitement de l'image
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('residences', 'public');
            $validated['image'] = '/storage/' . $imagePath;
        }

        // Création de la résidence
        $residence = Residence::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'short_description' => $validated['short_description'] ?? Str::limit($validated['description'], 100),
            'location' => $validated['location'],
            'price' => $validated['price_per_night'],
            'price_per_night' => $validated['price_per_night'],
            'size' => $validated['size'] ?? 50,
            'surface' => $validated['size'] ?? 50,
            'max_guests' => $validated['max_guests'],
            'floor' => $validated['floor'] ?? 'RDC',
            'rating' => 4.5,
            'availability_status' => 'available',
            'image' => $validated['image'] ?? null,
            'is_featured' => $validated['is_featured'] ?? false,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        // Associer les équipements
        if (!empty($validated['amenities'])) {
            $residence->amenities()->sync($validated['amenities']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Résidence créée avec succès!',
            'residence' => $residence->load('images', 'amenities')
        ]);
    }

    /**
     * Show the form for editing a residence
     */
    public function edit(Residence $residence)
    {
        $residence->load(['amenities', 'images' => function($query) {
            $query->orderBy('order')->orderBy('created_at');
        }]);
        $amenities = Amenity::all();

        return response()->json([
            'residence' => $residence,
            'amenities' => $amenities
        ]);
    }

    /**
     * Update a residence
     */
    public function update(Request $request, Residence $residence)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:255',
            'location' => 'required|string|max:255',
            'price_per_night' => 'required|numeric|min:0',
            'size' => 'nullable|numeric|min:0',
            'max_guests' => 'required|integer|min:1',
            'floor' => 'nullable|string|max:100',
            'amenities' => 'nullable|array',
            'amenities.*' => 'exists:amenities,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'is_featured' => 'boolean'
        ]);

        // Traitement de l'image
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($residence->image && Storage::disk('public')->exists(str_replace('/storage/', '', $residence->image))) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $residence->image));
            }

            $imagePath = $request->file('image')->store('residences', 'public');
            $validated['image'] = '/storage/' . $imagePath;
        }

        // Mise à jour
        $residence->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'short_description' => $validated['short_description'] ?? Str::limit($validated['description'], 100),
            'location' => $validated['location'],
            'price' => $validated['price_per_night'],
            'price_per_night' => $validated['price_per_night'],
            'size' => $validated['size'] ?? $residence->size,
            'surface' => $validated['size'] ?? $residence->surface,
            'max_guests' => $validated['max_guests'],
            'floor' => $validated['floor'] ?? $residence->floor,
            'image' => $validated['image'] ?? $residence->image,
            'is_featured' => $validated['is_featured'] ?? $residence->is_featured,
            'is_active' => $validated['is_active'] ?? $residence->is_active,
        ]);

        // Mettre à jour les équipements
        if (isset($validated['amenities'])) {
            $residence->amenities()->sync($validated['amenities']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Résidence mise à jour avec succès!',
            'residence' => $residence->load('images', 'amenities')
        ]);
    }

    /**
     * Toggle residence status
     */
    public function toggleStatus(Residence $residence)
    {
        $residence->update(['is_active' => !$residence->is_active]);

        return response()->json([
            'success' => true,
            'message' => $residence->is_active ? 'Résidence activée' : 'Résidence désactivée',
            'status' => $residence->is_active
        ]);
    }

    /**
     * Remove a residence
     */
    public function destroy(Residence $residence)
    {
        // Vérifier s'il y a des réservations actives
        $activeBookings = $residence->bookings()
            ->whereIn('status', ['pending', 'confirmed'])
            ->count();

        if ($activeBookings > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Impossible de supprimer une résidence avec des réservations actives.'
            ], 422);
        }

        // Supprimer l'image
        if ($residence->image && Storage::disk('public')->exists(str_replace('/storage/', '', $residence->image))) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $residence->image));
        }

        // Supprimer les relations
        $residence->amenities()->detach();
        $residence->images()->delete();

        // Supprimer la résidence
        $residence->delete();

        return response()->json([
            'success' => true,
            'message' => 'Résidence supprimée avec succès!'
        ]);
    }

    /**
     * Upload multiple images for a residence
     */
    public function uploadImages(Request $request, Residence $residence)
    {
        $request->validate([
            'images' => 'required|array|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB max per image
        ]);

        $uploadedImages = [];

        foreach ($request->file('images') as $index => $file) {
            // Generate unique filename
            $filename = time() . '_' . $index . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            
            // Store in residences folder
            $path = $file->storeAs('residences', $filename, 'public');

            // Create image record
            $image = $residence->images()->create([
                'image_path' => $path,
                'alt_text' => $residence->name . ' - Image ' . ($index + 1),
                'is_primary' => $residence->images()->count() === 0 && $index === 0, // First image is primary if no images exist
                'order' => $residence->images()->max('order') + 1 + $index,
            ]);

            $uploadedImages[] = [
                'id' => $image->id,
                'url' => asset('storage/' . $path),
                'is_primary' => $image->is_primary,
                'order' => $image->order,
            ];
        }

        return response()->json([
            'success' => true,
            'message' => count($uploadedImages) . ' image(s) ajoutée(s) avec succès!',
            'images' => $uploadedImages
        ]);
    }

    /**
     * Delete a residence image
     */
    public function deleteImage(Residence $residence, $imageId)
    {
        $image = $residence->images()->findOrFail($imageId);
        
        // Delete physical file
        if (Storage::disk('public')->exists($image->image_path)) {
            Storage::disk('public')->delete($image->image_path);
        }

        // If this was the primary image, set another image as primary
        if ($image->is_primary && $residence->images()->count() > 1) {
            $nextPrimary = $residence->images()
                ->where('id', '!=', $image->id)
                ->orderBy('order')
                ->first();
            
            if ($nextPrimary) {
                $nextPrimary->update(['is_primary' => true]);
            }
        }

        // Delete the image record
        $image->delete();

        return response()->json([
            'success' => true,
            'message' => 'Image supprimée avec succès!'
        ]);
    }

    /**
     * Set an image as primary
     */
    public function setPrimaryImage(Residence $residence, $imageId)
    {
        // Remove primary status from all images
        $residence->images()->update(['is_primary' => false]);

        // Set the selected image as primary
        $image = $residence->images()->findOrFail($imageId);
        $image->update(['is_primary' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Image principale mise à jour!'
        ]);
    }

    /**
     * Reorder images
     */
    public function reorderImages(Request $request, Residence $residence)
    {
        $request->validate([
            'image_ids' => 'required|array',
            'image_ids.*' => 'integer|exists:residence_images,id'
        ]);

        foreach ($request->image_ids as $index => $imageId) {
            $residence->images()
                ->where('id', $imageId)
                ->update(['order' => $index + 1]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Ordre des images mis à jour!'
        ]);
    }
}
