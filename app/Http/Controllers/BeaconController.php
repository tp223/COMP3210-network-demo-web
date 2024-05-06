<?php

namespace App\Http\Controllers;

use App\Models\Beacon;
use Illuminate\Http\Request;

class BeaconController extends Controller
{
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
}
