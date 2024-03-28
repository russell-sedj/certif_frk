<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategorieController;
use App\Models\Categorie;
use App\Models\User;

Route::get('/', function () {
    return view('auth.login');
});
Route::get('/dashboard', function () {
    $categories=Categorie::all();
    return view('mydashboard', compact('categories'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/attestation', function(){
    return view('pages/attestation');
})->middleware(['auth', 'verified'])->name('attestation');

Route::middleware('auth')->group(function () {
    Route::get('/profile/{user}', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile', [ProfileController::class, 'myAccount'])->name('profile.myAccount');
    Route::patch('/profile/{user}', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/{user}', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::resource('categories', CategorieController::class);

Route::get('/agents', function () {
    $users=User::all();
    return view('agents.index', compact('users'));
})->name('agents');

require __DIR__.'/auth.php';
