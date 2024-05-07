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
        // Return the map create view
        return view('map.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'status' => 'required|string|max:255',
        ]);

        // Validate statuses
        if (!in_array($request->status, ['hidden', 'published'])) {
            return back()->withErrors(['status' => 'Invalid status']);
        }

        // Add public url if the map is published
        if ($request->status == 'published') {
            // Generate random public url key using numbers and letters
            $url_key = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 15);
        }

        // Store the image in the storage
        // $map->map_base_image = $request->file('map_base_image')->store('maps');
        // Check if the request has a file
        if ($request->hasFile('map_base_image')) {
            $request->validate([
                'map_base_image' => 'required|image|mimes:png|max:204800',
            ]);
            // Store the image in the storage with the name map_{map_id}.png
            $map->map_base_image = $request->file('map_base_image')->storeAs('maps', 'map_' . $map->id . '.png');
            // Update the location of the image
            $map->map_base_image = 'maps/map_' . $map->id . '.png';
        }

        // Create the map
        $map = new Map();
        $map->name = $request->name;
        $map->description = $request->description;
        $map->status = $request->status;
        $map->owner_id = Auth::id();
        if ($request->status == 'published' && isset($url_key)) {
            $map->public_url = $url_key;
        }

        // Save the map
        $map->save();

        // Redirect back to the map edit page
        return redirect()->route('map.edit', $map->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Map $map, $key)
    {
        // Get the map from the public url
        $map = Map::where('public_url', $key)->first();

        // Check if the map exists
        if (!$map) {
            return view('map.not-found');
        }

        // Check if the map is published
        if ($map->status != 'published') {
            return view('map.not-found');
        }

        // Return the map view
        return view('map.show', compact('map'));
    }

    /**
     * Get the beacons for the map.
     */
    public function beacons(Map $map, $key)
    {
        // Get the map from the public url
        $map = Map::where('public_url', $key)->first();

        // Check if the map exists
        if (!$map) {
            return response()->json(['status' => 'error', 'message' => 'Map not found']);
        }

        // Check if the map is published
        if ($map->status != 'published') {
            return response()->json(['status' => 'error', 'message' => 'Map not found']);
        }

        // Get all beacons for the map
        $beacons = $map->beacons->map(function ($beacon) {
            if ($beacon->status != 'enabled') {
                return null;
            }
            return [
                'beacon_id' => $beacon->id,
                'name' => $beacon->name,
                'description' => $beacon->description,
                'latitude' => $beacon->latitude,
                'longitude' => $beacon->longitude,
                'bt_address' => $beacon->bt_address,
            ];
        });

        // Return beacons as JSON response
        return response()->json($beacons);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $map)
    {
        // Get the map from the users maps
        $map = Auth::user()->maps->where('id', $map)->first();

        // Get all beacons for the map
        $beacons = $map->beacons;

        // Get all beacons that are not assigned to the map
        $unassignedBeacons = Auth::user()->beacons->diff($beacons);

        // Return the map edit view
        return view('map.edit', compact('map', 'beacons', 'unassignedBeacons'));
    }

    /**
     * Get the base image for the map.
     */
    public function baseImage(Request $request, $mapId)
    {
        $mapPublic = Map::where('public_url', $mapId)->first();
        $map = null;
        // Check if the request is authenticated
        if (Auth::check()) {
            // Get the map from the users maps
            $map = Auth::user()->maps->where('id', $mapId)->first();
        }

        if (!$map && !$mapPublic) {
            return response()->file(resource_path('images/upload-a-map.png'));
        }

        if ($mapPublic && $mapPublic->status == 'published') {
            $map = $mapPublic;
        }

        // Check if the map exists
        if (!$map) {
            return response()->file(resource_path('images/upload-a-map.png'));
        }

        if ($map->public_url == $mapId) {
            // Check if the map is published
            if ($map->status != 'published') {
                $response = response()->file(resource_path('images/upload-a-map.png'));
                $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
                $response->headers->set('Pragma', 'no-cache');
                $response->headers->set('Expires', '0');
                return $response;
            }
        }

        // Check if the map has a base image otherwise return the default image
        if ($map->map_base_image) {
            // Disable caching
            $response = response()->file(storage_path('app/' . $map->map_base_image));
            $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', '0');
            return $response;
        } else {
            // Disable caching
            $response = response()->file(resource_path('images/upload-a-map.png'));
            $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', '0');
            return $response;
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $map)
    {
        // Get the map from the users maps
        $map = Auth::user()->maps->where('id', $map)->first();

        if (!$map) {
            return back()->withErrors(['map' => 'Map not found']);
        }

        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'status' => 'required|string|max:255',
        ]);

        // Validate statuses
        if (!in_array($request->status, ['hidden', 'published'])) {
            return back()->withErrors(['status' => 'Invalid status']);
        }

        // Add public url if the map is published and doesn't have one
        if ($request->status == 'published' && !$map->public_url) {
            // Generate random public url key using numbers and letters
            $url_key = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 15);
            $map->public_url = $url_key;
        }

        // Store the image in the storage
        // $map->map_base_image = $request->file('map_base_image')->store('maps');
        // Check if the request has a file
        if ($request->hasFile('map_base_image')) {
            $request->validate([
                'map_base_image' => 'required|image|mimes:png|max:204800',
            ]);
            // Store the image in the storage with the name map_{map_id}.png
            $map->map_base_image = $request->file('map_base_image')->storeAs('maps', 'map_' . $map->id . '.png');
            // Update the location of the image
            $map->map_base_image = 'maps/map_' . $map->id . '.png';
        }

        // Update the map
        $map->name = $request->name;
        $map->description = $request->description;
        $map->status = $request->status;

        // Save the map
        $map->save();

        // Redirect back to the map edit page
        return redirect()->route('map.edit', $map->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $map)
    {
        // Get the map from the users maps
        $map = Auth::user()->maps->where('id', $map)->first();

        // Delete the map
        $map->delete();

        // Redirect to the dashboard
        return redirect()->route('dashboard');
    }

    /**
     * Add a beacon to the map.
     */
    public function addBeacon(Request $request, $map)
    {
        // Get the map from the users maps
        $map = Auth::user()->maps->where('id', $map)->first();

        // Validate the request
        $request->validate([
            'beacon_id' => 'required|integer'
        ]);

        // Check if the beacon is owned by the user
        $beacon = Auth::user()->beacons->where('id', $request->beacon_id)->first();

        if (!$beacon) {
            return redirect()->back()->withErrors(['beacon_id' => 'Beacon not found']);
        }

        // Attach the beacon to the map
        $beacon->map_id = $map->id;
        $beacon->save();

        // Redirect back to the map edit page
        return redirect()->route('map.edit', $map->id);
    }

    /**
     * Remove a marker from the map.
     */
    public function removeBeacon(Request $request, $map)
    {
        // Get the map from the users maps
        $map = Auth::user()->maps->where('id', $map)->first();

        // Validate the request
        $request->validate([
            'beacon_id' => 'required|integer',
        ]);

        // Check if the beacon is owned by the user
        $beacon = Auth::user()->beacons->where('id', $request->beacon_id)->first();

        if (!$beacon) {
            return redirect()->back()->withErrors(['beacon_id' => 'Beacon not found']);
        }

        // Remove the beacon from the map
        $beacon->map_id = null;
        $beacon->latitude = null;
        $beacon->longitude = null;
        $beacon->status = 'disabled';
        $beacon->save();

        // Redirect back to the map edit page
        return redirect()->route('map.edit', $map->id);
    }

    /**
     * Get the markers for the map.
     */
    public function markers(Request $request, $map)
    {
        // Get the map from the users maps
        $map = Auth::user()->maps->where('id', $map)->first();

        // Get all markers for the map
        $markers = $map->beacons->map(function ($beacon) {
            return [
                'beacon_id' => $beacon->id,
                'name' => $beacon->name,
                'description' => $beacon->description,
                'latitude' => $beacon->latitude,
                'longitude' => $beacon->longitude,
                'status' => $beacon->status,
            ];
        });

        // Return markers as JSON response
        return response()->json($markers);
    }

    /**
     * Update the markers for the map.
     */
    public function updateMarker(Request $request, $map, $marker_id)
    {
        // Get the map from the users maps
        $map = Auth::user()->maps->where('id', $map)->first();

        // Validate the request
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        // Get the beacon from the map
        $beacon = $map->beacons->where('id', $marker_id)->first();

        // Check if the beacon is owned by the user
        if (!$beacon) {
            return response()->json(['status' => 'error', 'message' => 'Beacon not found']);
        }

        // Update the beacon
        $beacon->latitude = $request->latitude;
        $beacon->longitude = $request->longitude;
        $beacon->save();

        // Return success
        return response()->json(['status' => 'success']);
    }

    /**
     * Add a marker to the map.
     */
    public function addMarker(Request $request, $map, $marker_id)
    {
        // Get the map from the users maps
        $map = Auth::user()->maps->where('id', $map)->first();

        // Validate the request
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        // Get the beacon from the map
        $beacon = $map->beacons->where('id', $marker_id)->first();

        // Check if the beacon is owned by the user
        if (!$beacon) {
            return response()->json(['status' => 'error', 'message' => 'Beacon not found']);
        }

        // Update the beacon
        $beacon->latitude = $request->latitude;
        $beacon->longitude = $request->longitude;
        $beacon->status = 'enabled';
        $beacon->save();

        // Return success
        return response()->json(['status' => 'success']);
    }
}
