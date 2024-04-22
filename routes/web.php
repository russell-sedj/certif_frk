<?php

use App\Http\Controllers\DemandeController;
use App\Http\Controllers\FormationController;
use App\Http\Controllers\ProfileController;
use App\Models\SessionproUser;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategorieController;
use App\Models\Categorie;
use App\Models\User;
use App\Http\Controllers\LieuController;
use App\Http\Controllers\SessionproController;
use App\Http\Controllers\PresenceController;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    if(Auth::check()){
    return redirect()->route('dashboard');
    }
    else{                      
    return view('auth.login');
    }
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
    Route::get('/sessionpros/dashboard', 'dashboard')->name('sessionpros.dashboard');
    Route::post('/sessionpros', 'store')->name('sessionpros.store');
    Route::get('/sessionpros/dashboard', 'dashboard')->name('sessionpros.dashboard');
    Route::get('/sessionpros/{formation}', 'index')->name('sessionpros.index');
    Route::put('/sessionpros/{sessionpro}/{user}/noterParticipant', 'noterParticipant')->name('sessionpros.noterParticipant');
    Route::put('/sessionpros/{sessionpro}', 'update')->name('sessionpros.update');
    Route::put('/sessionpros/{sessionpro}/actionInscription', 'actionInscription')->name('sessionpros.actionInscription');
    Route::put('/sessionpros/{sessionpro}/openRequest', 'openRequest')->name('sessionpros.openRequest');
    Route::delete('/sessionpros/{sessionpro}/{formation}', 'destroy')->name('sessionpros.destroy');
    Route::get('/sessionpros/{formation}/create', 'create')->name('sessionpros.create');
    Route::get('/sessionpros/{sessionpro}/edit', 'edit')->name('sessionpros.edit');
    Route::get('/sessionpros/{sessionpro}/show', 'show')->name('sessionpros.show');
    Route::get('/sessionpros/{sessionpro}/participants','participants')->name('sessionpros.participants');
});

Route::controller(PresenceController::class)->group(function(){
    Route::post('/presences/{sessionpro}/store', 'store')->name('presences.store');
    Route::get('/presences/participantsPresent/{sessionpro}/{presence}', 'participantsPresent')->name('presences.participantsPresent');
    Route::get('/presences/{sessionpro}', 'index')->name('presences.index');
    Route::put('/presences/{presence}/activate', 'activate')->name('presences.activate');
    Route::delete('/presences/deleteParticipantPresent/{presence}/{participant}', 'deleteParticipantPresent')->name('presences.deleteParticipantPresent');
    Route::delete('/presences/{presence}/{sessionpro}', 'destroy')->name('presences.destroy');
});

Route::put('/inscription/{sessionpro}', [RegisteredUserController::class, 'enrollment'])->name('inscription');

Route::get('/mesformations', function (Request $request) {
    $user=$request->user();
    return view('mesformations', compact('user'));
})->name('mesformations');

Route::get('/users/marquerPresence/{sessionpro}/{user}', [RegisteredUserController::class, 'marquerPresenceView'])->name('users.marquerPresenceView');
Route::post('/users/marquerPresence/{presence}/{user}', [RegisteredUserController::class, 'marquerPresence'])->name('users.marquerPresence');

Route::controller(DemandeController::class)->group(function(){
    Route::get('/mesdemandes', 'indexAgent')->name('demandesAgent.index');
    Route::get('/demandes', 'indexSuperviseur')->name('demandesSup.index');
    Route::get('/demandes/{sessionpro}/{user}/store', 'store')->name('demandes.store');
    Route::put('/demandes/{demande}', 'update')->name('demandes.update');
    //Route::put('/presences/{presence}/activate', 'activate')->name('presences.activate');
   // Route::delete('/presences/{presence}/{sessionpro}', 'destroy')->name('presences.destroy');
    // Route::get('/presences/{sessionpro}/create', 'create')->name('presences.create');
    // Route::get('/presences/{presence}/edit', 'edit')->name('presences.edit');
});

require __DIR__.'/auth.php';