<?php

namespace App\Http\Controllers;

use App\Models\Map;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MapController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function show(Map $map)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $map)
    {
        // Get the map from the users maps
        $map = Auth::user()->maps->where('id', $map)->first();

        // Return the map edit view
        return view('map.edit', compact('map'));
    }

    /**
     * Get the base image for the map.
     */
    public function baseImage(Request $request, $map)
    {
        // Get the map from the users maps
        $map = Auth::user()->maps->where('id', $map)->first();

        // Check if the map has a base image otherwise return the default image
        if ($map->map_base_image) {
            return response()->file(storage_path('app/' . $map->map_base_image));
        } else {
            return response()->file(resource_path('images/upload-a-map.png'));
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Map $map)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Map $map)
    {
        //
    }
}
