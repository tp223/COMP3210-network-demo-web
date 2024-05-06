<?php

namespace App\Http\Controllers;

use App\Models\BeaconSetup;
use Illuminate\Http\Request;

class BeaconSetupController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function register(Request $request)
    {
        // Validate the request
        $request->validate([
            'serial_number' => 'required|string|max:255',
            'user_key' => 'required|string|max:255',
        ]);

        // Generate a bearer token
        $bearerToken = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 60);

        // Create a new beacon setup
        $beaconSetup = new BeaconSetup();
        $beaconSetup->beacon_id = $request->beacon_id;
        $beaconSetup->serial_number = $request->serial_number;
        $beaconSetup->user_key = $request->user_key;
        $beaconSetup->api_key = $bearerToken;
        $beaconSetup->save();

        // Return the beacon setup
        return response()->json($beaconSetup);
    }

    /**
     * Poll the beacon setup.
     */
    public function pollSetup(Request $request)
    {
        // Get the bearer token
        $bearerToken = $request->bearerToken();

        // Find the beacon setup using the bearer token
        $beaconSetup = BeaconSetup::where('api_key', $bearerToken)->first();

        // Check if the beacon setup exists
        if (!$beaconSetup) {
            return response()->json(['status' => 'error', 'message' => 'Beacon setup not found']);
        }

        // Return the beacon setup
        if ($beaconSetup->status == 'pending') {
            return response()->json(['status' => 'pending']);
        } else {
            return response()->json(['status' => 'completed']);
        }
    }
}
