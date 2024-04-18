<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Sessionpro;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Models\Presence;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'matricule'=>['required', 'string','unique:'.User::class],
            'surname'=>['required', 'string', 'max:255'],
            'sexe'=>'required',
            'role',
            'titre',
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'matricule'=> $request->matricule,
            'surname'=>$request->surname,
            'sexe'=>$request->sexe,
            'role'=>$request->role,
            'titre'=>$request->titre,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));


        return redirect(route('agents', absolute: false));
    }

    public function enrollment(Request $request, Sessionpro $sessionpro)
    {
        $user=$request->user();
        $sessionpro=Sessionpro::find($sessionpro->id);
        $user->sessionpros->contains($sessionpro->id)?$user->sessionpros()->detach($sessionpro->id):$user->sessionpros()->attach($sessionpro->id); 
        
        return redirect()->back();
        //return redirect()->route('sessionpros.index', $sessionpro->formation_id);
    }
    
    public function marquerPresenceView(Sessionpro $sessionpro, User $user)
    {
        $presences = $sessionpro->presences;
        return view('users.marquerPresenceView', compact('user', 'sessionpro', 'presences'));
    }

    public function marquerPresence(Presence $presence, User $user, Request $request)
    {
        $num_presence = $request->num_presence;
        
        if ($presence->users()->wherePivot('num_presence', $num_presence)->exists()) {
            return redirect()->back()->with('error', 'Numéro de présence déjà utilisé');
        }
        else {
            $presence->users()->attach($user->id, ['num_presence'=>$num_presence]);
            return redirect()->back();
        }
        
    }
}