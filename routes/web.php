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

// Routes requiring authentication
Route::middleware(['auth'])->group(function () {
    Route::get('/logout', 'App\Http\Controllers\UserController@logout')->name('logout');
    Route::get('/dashboard', 'App\Http\Controllers\UserController@dashboard')->name('dashboard');
    Route::get('/dashboard/maps/add', 'App\Http\Controllers\MapController@create')->name('map.create');
    Route::post('/dashboard/maps/add', 'App\Http\Controllers\MapController@store')->name('map.store');
    Route::get('/dashboard/maps/{map}', 'App\Http\Controllers\MapController@edit')->name('map.edit');
    Route::put('/dashboard/maps/{map}', 'App\Http\Controllers\MapController@update')->name('map.update');
    Route::delete('/dashboard/maps/{map}', 'App\Http\Controllers\MapController@destroy')->name('map.destroy');
    Route::post('/dashboard/maps/{map}/beacons', 'App\Http\Controllers\MapController@addBeacon')->name('map.beacon.store');
    Route::put('/dashboard/maps/{map}/beacons', 'App\Http\Controllers\MapController@updateBeacon')->name('map.beacon.update');
    Route::delete('/dashboard/maps/{map}/beacons', 'App\Http\Controllers\BeaconController@removeBeacon')->name('map.beacon.destroy');
    Route::get('/dashboard/maps/{map}/base-image.png', 'App\Http\Controllers\MapController@baseImage')->name('map.base-image');
});
