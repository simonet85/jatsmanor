<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Room::query();
        
        // Apply filters
        if ($request->type) {
            $query->where('type', $request->type);
        }
        if ($request->price_max) {
            $query->where('price', '<=', $request->price_max);
        }
        
        $rooms = $query->paginate(9);
        $resultsCount = $rooms->total();
        
        $additionalFilters = [
            [
                'name' => 'location',
                'label' => 'Localisation',
                'placeholder' => 'Toutes les zones',
                'options' => [
                    'cocody' => 'Cocody',
                    'plateau' => 'Plateau', 
                    'marcory' => 'Marcory'
                ]
            ]
        ];

        $pageTitle = 'Nos Résidences Premium';
        $pageDescription = 'Découvrez notre sélection de chambres et suites haut de gamme.';

        return view('residences', compact('rooms', 'resultsCount', 'additionalFilters', 'pageTitle', 'pageDescription'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
