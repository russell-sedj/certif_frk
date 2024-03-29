<?php

use App\Http\Controllers\FormationController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategorieController;
use App\Models\Categorie;
use App\Models\User;
use App\Http\Controllers\LieuController;
use App\Http\Controllers\SessionproController;

Route::get('/', function () {
    return view('auth.login');
});
Route::get('/dashboard', function () {
    $categories=Categorie::all();
    return view('mydashboard', compact('categories'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/attestation', function(){
    return view('attestation');
})->middleware(['auth', 'verified'])->name('attestation');

Route::middleware('auth')->group(function () {
    Route::get('/profile/myAccount', [ProfileController::class, 'myAccount'])->name('profile.myAccount');
    Route::get('/profile/{user}', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/{user}', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/{user}', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('categories', CategorieController::class);

Route::get('/agents', function () {
    $users=User::all();
    return view('agents.index', compact('users'));
})->name('agents');

require __DIR__.'/auth.php';

Route::resource('lieus', LieuController::class);

Route::controller(FormationController::class)->group(function () {
    Route::post('/formations','store')->name('formations.store');
    Route::get('/formations/{categorie}', 'index')->name('formations.index');
    Route::put('/formations/{formation}', 'update')->name('formations.update');
    Route::delete('/formation/{formation}/{categorie}', 'destroy')->name('formations.destroy');
    Route::get('/formations/{categorie}/create','create')->name('formations.create');
    Route::get('/formations/{formation}/edit','edit')->name('formations.edit');
});

Route::controller(SessionproController::class)->group(function(){
    Route::post('/sessionpros', 'store')->name('sessionpros.store');
    Route::get('/sessionpros/{formation}', 'index')->name('sessionpros.index');
    Route::put('/sessionpros/{sessionpro}', 'update')->name('sessionpros.update');
    Route::delete('/sessionpros/{sessionpro}/{formation}', 'destroy')->name('sessionpros.destroy');
    Route::get('/sessionpros/{formation}/create', 'create')->name('sessionpros.create');
    Route::get('/sessionpros/{sessionpro}/edit', 'edit')->name('sessionpros.edit');
});

require __DIR__.'/auth.php';