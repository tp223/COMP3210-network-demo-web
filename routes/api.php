<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('beacon/register', 'App\Http\Controllers\BeaconSetupController@register')->name('beacon.register');
Route::get('beacon/poll-setup', 'App\Http\Controllers\BeaconSetupController@pollSetup')->name('beacon.poll-setup');

Route::get('beacon/get-config', 'App\Http\Controllers\BeaconController@getConfig')->name('beacon.get-config');
Route::get('beacon/heartbeat', 'App\Http\Controllers\BeaconController@heartbeat')->name('beacon.heartbeat');