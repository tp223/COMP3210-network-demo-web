<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Display the user dashboard.
     */
    public function dashboard()
    {
        // Get the authenticated user
        $user = auth()->user();

        // Get the user's maps
        $maps = $user->maps;

        // Get the user's beacons
        $beacons = $user->beacons;

        // Return the dashboard view
        return view('dashboard', compact('user', 'maps', 'beacons'));
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
        // Validate the request
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'password_confirmation' => 'required|same:password'
        ]);

        // Create a new user
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        // Redirect to the login page
        return redirect()->route('login');
    }

    /**
     * Authenticate the user
     */
    public function authenticate(Request $request)
    {
        // Validate the request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Find the user
        $user = User::where('email', $request->email)->first();

        // Check if the user exists
        if ($user) {
            // Check if the password is correct
            if (Hash::check($request->password, $user->password)) {
                // Authenticate the user
                auth()->login($user);

                // Redirect to intended page
                return redirect()->intended(route('dashboard'));
            }
        }

        // Redirect to the login page
        return redirect()->route('login');
    }

    /**
     * Logout the user
     */
    public function logout()
    {
        // Logout the user
        auth()->logout();

        // Redirect to the login page
        return redirect()->route('login');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
