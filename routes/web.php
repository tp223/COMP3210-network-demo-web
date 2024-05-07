<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('index');
})->name('index');

Route::get('/login', function () {
    return view('login');
})->name('login');
Route::post('/login', 'App\Http\Controllers\UserController@authenticate')->name('login.authenticate');

Route::get('/register', function () {
    return view('register');
})->name('register');
Route::post('/register', 'App\Http\Controllers\UserController@store')->name('register.store');

Route::get('/map/{key}', 'App\Http\Controllers\MapController@show')->name('map.show');
Route::get('/map/{key}/beacons', 'App\Http\Controllers\MapController@beacons')->name('map.beacons');
Route::get('/map/{key}/base-image.png', 'App\Http\Controllers\MapController@baseImage')->name('map.base-image');

// Routes requiring authentication
Route::middleware(['auth'])->group(function () {
    Route::get('/logout', 'App\Http\Controllers\UserController@logout')->name('logout');
    Route::get('/dashboard', 'App\Http\Controllers\UserController@dashboard')->name('dashboard');

    // Routes to add and configure a map
    Route::get('/dashboard/maps/add', 'App\Http\Controllers\MapController@create')->name('map.create');
    Route::post('/dashboard/maps/add', 'App\Http\Controllers\MapController@store')->name('map.store');
    Route::get('/dashboard/maps/{map}', 'App\Http\Controllers\MapController@edit')->name('map.edit');
    Route::put('/dashboard/maps/{map}', 'App\Http\Controllers\MapController@update')->name('map.update');
    Route::delete('/dashboard/maps/{map}', 'App\Http\Controllers\MapController@destroy')->name('map.destroy');
    Route::post('/dashboard/maps/{map}/beacons', 'App\Http\Controllers\MapController@addBeacon')->name('map.beacon.store');
    Route::put('/dashboard/maps/{map}/beacons', 'App\Http\Controllers\MapController@updateBeacon')->name('map.beacon.update');
    Route::delete('/dashboard/maps/{map}/beacons', 'App\Http\Controllers\MapController@removeBeacon')->name('map.beacon.destroy');
    Route::get('/dashboard/maps/{map}/base-image.png', 'App\Http\Controllers\MapController@baseImage')->name('private.map.base-image');

    // Leaflet routes for displaying the map
    Route::get('/dashboard/maps/{map}/markers', 'App\Http\Controllers\MapController@markers')->name('map.markers');
    Route::put('/dashboard/maps/{map}/markers/{marker_id}', 'App\Http\Controllers\MapController@updateMarker')->name('map.markers.update');
    Route::post('/dashboard/maps/{map}/markers/{marker_id}', 'App\Http\Controllers\MapController@addMarker')->name('map.markers.store');
    Route::delete('/dashboard/maps/{map}/markers', 'App\Http\Controllers\MapController@removeMarker')->name('map.markers.destroy');

    // Routes to add and configure a beacon
    Route::get('/dashboard/beacons', 'App\Http\Controllers\BeaconController@index')->name('beacon.index');
    Route::get('/dashboard/beacons/add/{setup_key}', 'App\Http\Controllers\BeaconController@create')->name('beacon.create');
    Route::put('/dashboard/beacons/add/{setup_key}', 'App\Http\Controllers\BeaconController@store')->name('beacon.store');
    Route::get('/dashboard/beacons/edit/{beacon_id}', 'App\Http\Controllers\BeaconController@edit')->name('beacon.edit');
    Route::put('/dashboard/beacons/edit/{beacon_id}', 'App\Http\Controllers\BeaconController@update')->name('beacon.update');
    Route::delete('/dashboard/beacons/edit/{beacon_id}', 'App\Http\Controllers\BeaconController@destroy')->name('beacon.destroy');
});
