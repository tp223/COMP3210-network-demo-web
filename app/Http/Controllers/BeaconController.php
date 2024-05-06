<?php

namespace App\Http\Controllers;

use App\Models\Beacon;
use Illuminate\Http\Request;
use App\Models\BeaconSetup;

class BeaconController extends Controller
{
    /**
     * Show the beacons of the user.
     */
    public function index()
    {
        // Get the beacons of the user
        $beacons = Beacon::where('owner_id', auth()->id())->get();

        // Return the beacons
        return view('beacon.index', ['beacons' => $beacons]);
    }

    /**
     * Show setup page for the beacon.
     */
    public function create($setupKey)
    {
        // Find the beacon setup using the setup key
        $beaconSetup = BeaconSetup::where('user_key', $setupKey)->first();

        // Check if the beacon setup exists
        if (!$beaconSetup) {
            // Redirect to the dashboard with an error message
            return redirect()->route('dashboard')->with('error', 'Beacon setup not found. Please try again in a few moments...');
        }

        // Check if the beacon setup is already used
        $beacon = Beacon::where('api_key', $beaconSetup->api_key)->first();
        if ($beacon || $beaconSetup->status == 'complete') {
            // Redirect to the dashboard with an error message
            return redirect()->route('dashboard')->with('error', 'Beacon setup already used. Please reset the beacon setup and try again...');
        }

        // Return the beacon setup
        return view('beacon.create', ['beaconSetup' => $beaconSetup]);   
    }

    /**
     * Store the beacon in the database.
     */
    public function store(Request $request, $setupKey)
    {
        // Find the beacon setup using the setup key
        $beaconSetup = BeaconSetup::where('user_key', $setupKey)->first();

        // Check if the beacon setup exists
        if (!$beaconSetup) {
            // Redirect to the dashboard with an error message
            return redirect()->route('dashboard')->with('error', 'Beacon setup not found. Please try again in a few moments...');
        }

        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'string|nullable|max:255',
        ]);

        // Change status to complete
        $beaconSetup->status = 'complete';
        $beaconSetup->save();

        // Create the beacon
        $beacon = new Beacon();
        $beacon->name = $request->name;
        $beacon->description = $request->description;
        $beacon->api_key = $beaconSetup->api_key;
        $beacon->owner_id = auth()->id();
        $beacon->status = 'disabled';
        $beacon->save();

        // Redirect to the dashboard with a success message
        return redirect()->route('dashboard')->with('success', 'Beacon created successfully');
    }

    /**
     * Get the configuration of the beacon.
     */
    public function getConfig(Request $request)
    {
        // Get the bearer token
        $bearerToken = $request->bearerToken();

        // Find the beacon using the bearer token
        $beacon = Beacon::where('api_key', $bearerToken)->first();

        // Check if the beacon exists
        if (!$beacon) {
            return response()->json(['status' => 'error', 'message' => 'Beacon not found']);
        }

        // Hide the api key
        $beacon->api_key = null;

        // Return the beacon configuration
        return response()->json($beacon);
    }

    /**
     * Heartbeat the beacon.
     */
    public function heartbeat(Request $request)
    {
        // Get the bearer token
        $bearerToken = $request->bearerToken();

        // Find the beacon using the bearer token
        $beacon = Beacon::where('api_key', $bearerToken)->first();

        // Check if the beacon exists
        if (!$beacon) {
            return response()->json(['status' => 'error', 'message' => 'Beacon not found']);
        }

        // Update the last heartbeat
        $beacon->last_heartbeat = now();
        $beacon->save();

        // Return success
        return response()->json(['status' => 'success']);
    }

    /**
     * Beacon edit page
     */
    public function edit(Request $request, $beaconId)
    {
        // Find the beacon using the beacon id and the owner id
        $beacon = Auth::user()->beacons()->where('id', $beaconId)->first();

        // Return the beacon edit page
        return view('beacon.edit', ['beacon' => $beacon]);
    }
}
