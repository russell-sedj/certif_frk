<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategorieController;
use App\Models\Categorie;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $categories=Categorie::all();
    return view('mydashboard', compact('categories'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/attestation', function(){
    return view('pages/attestation');
})->middleware(['auth', 'verified'])->name('attestation');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::resource('categories', CategorieController::class);
require __DIR__.'/auth.php';
