<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
});

// Profile Routes
Route::resource('profiles', ProfileController::class);
Route::get('fetch', [ProfileController::class, 'fetch'])->name('fetch');
Route::get('deletedata', [ProfileController::class, 'deletedata'])->name('deletedata');
Route::post('update/{id}', [ProfileController::class, 'update'])->name('profiles.update');
Route::delete('profiles/{id}', [ProfileController::class, 'destroy'])->name('profiles.destroy');
