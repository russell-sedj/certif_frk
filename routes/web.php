<?php

use App\Http\Controllers\DemandeController;
use App\Http\Controllers\FormationController;
use App\Http\Controllers\ProfileController;
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
use App\Http\Middleware\EnsureUsersHasRole;
Route::get('/', function () {
    if(Auth::check()){
    if(in_array(auth()->user()->role, ['admin','superviseur'])){
    return redirect()->route('sessionpros.dashboard');
    }
    else{
     return redirect()->route('mesformations');     
    }
    }
    else{                      
    return view('auth.login');
    }
})->name('dashboard');

 Route::middleware(['auth', 'verified'])->group(function () { 
     Route::get('/formations', function () {
    $categories=Categorie::all();
    return view('mydashboard', compact('categories'));
    })->name('formations');

    Route::middleware(EnsureUsersHasRole::class.':participant')->group(function () {
        Route::get('/mesformations', function (Request $request) {
            $user=$request->user();
            return view('mesformations', compact('user'));
        })->name('mesformations');
        Route::get('/mesdemandes', [DemandeController::class,'indexAgent'])->name('demandesAgent.index');
        Route::get('/users/marquerPresence/{sessionpro}/{user}', [RegisteredUserController::class, 'marquerPresenceView'])->name('users.marquerPresenceView');
        Route::post('/users/marquerPresence/{presence}/{user}', [RegisteredUserController::class, 'marquerPresence'])->name('users.marquerPresence');
        Route::get('/demandes/{sessionpro}/{user}/store', [DemandeController::class,'store'])->name('demandes.store');   
    });
    Route::get('/profile/myAccount', [ProfileController::class, 'myAccount'])->name('profile.myAccount');

Route::middleware(EnsureUsersHasRole::class.':admin')->group(function () {
    Route::get('/agents', function () {
        $users=User::all()->sortBy('matricule');
        return view('agents.index', compact('users'));
    })->name('agents');
});

Route::middleware(EnsureUsersHasRole::class.':admin,superviseur')->group(function () {

        Route::get('/profile/{user}', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile/{user}', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile/{user}', [ProfileController::class, 'destroy'])->name('profile.destroy');

        Route::controller(FormationController::class)->group(function () {
        Route::post('/formations','store')->name('formations.store');
        Route::put('/formations/{formation}', 'update')->name('formations.update');
        Route::delete('/formation/{formation}/{categorie}', 'destroy')->name('formations.destroy');
        Route::get('/formations/{categorie}/create','create')->name('formations.create');
        Route::get('/formations/{formation}/edit','edit')->name('formations.edit');
    });

        Route::controller(SessionproController::class)->group(function(){
        Route::post('/sessionpros', 'store')->name('sessionpros.store');
        Route::get('/sessionpros/dashboard', 'dashboard')->name('sessionpros.dashboard');
        Route::put('/sessionpros/{sessionpro}/{user}/noterParticipant', 'noterParticipant')->name('sessionpros.noterParticipant');
        Route::put('/sessionpros/{sessionpro}', 'update')->name('sessionpros.update');
        Route::put('/sessionpros/{sessionpro}/actionInscription', 'actionInscription')->name('sessionpros.actionInscription');
        Route::put('/sessionpros/{sessionpro}/openRequest', 'openRequest')->name('sessionpros.openRequest');
        Route::delete('/sessionpros/{sessionpro}/{formation}', 'destroy')->name('sessionpros.destroy');
        Route::get('/sessionpros/{formation}/create', 'create')->name('sessionpros.create');
        Route::get('/sessionpros/{sessionpro}/edit', 'edit')->name('sessionpros.edit');
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

    
        Route::controller(DemandeController::class)->group(function(){
        Route::match(['get', 'put'], '/demandes', 'indexSuperviseur')->name('demandesSup.index');
        Route::post('/attestations', 'download')->name('demandes.download');
        Route::put('/demandes/update', 'update')->name('demandes.update');
        Route::put('/demandes/lastupdate', 'lastupdate')->name('demandes.lastupdate');
});    
        Route::resource('categories', CategorieController::class);
        Route::resource('lieus', LieuController::class);
});


Route::get('/formations/{categorie}', [FormationController::class,'index'])->name('formations.index');

Route::get('/sessionpros/{formation}', [SessionproController::class,'index'])->name('sessionpros.index');

Route::put('/inscription/{sessionpro}', [RegisteredUserController::class, 'enrollment'])->name('inscription');


Route::get('/lang/french.json', function () {
    return response()->file(public_path('lang/french.json'));
});

});


require __DIR__.'/auth.php';