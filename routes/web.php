<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LinkController;

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



Route::get('/', [App\Http\Controllers\LinkController::class, 'create'])->name('home');

Auth::routes();

Route::group(['middleware' => 'auth'], function () {

    // Links
    Route::resource('links', LinkController::class);
    // API
    Route::get('/api', [App\Http\Controllers\ApiController::class, 'index'])->name('api');
    Route::post('/api/generate', [App\Http\Controllers\ApiController::class, 'generate'])->name('api.generate');

});

// URL Redirect
Route::get('/{shortCode}', [App\Http\Controllers\LinkController::class, 'redirect']);
