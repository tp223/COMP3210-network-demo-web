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

// Routes requiring authentication
Route::middleware(['auth'])->group(function () {
    Route::get('/logout', 'App\Http\Controllers\UserController@logout')->name('logout');
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
