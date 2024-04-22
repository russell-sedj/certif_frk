<?php

namespace App\Http\Controllers;

use App\Models\Presence;
use App\Http\Requests\StorePresenceRequest;
use App\Http\Requests\UpdatePresenceRequest;
use App\Models\Sessionpro;
use App\Models\User;

class PresenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Sessionpro $sessionpro)
    {
        $presences = $sessionpro->presences;
        return view('presences.index', compact('presences','sessionpro'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Sessionpro $sessionpro)
    {
        // return view('presences.create', compact('sessionpro'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Sessionpro $sessionpro, StorePresenceRequest $request)
    {
        $lastPresence = $sessionpro->presences()->latest()->first();
        if($lastPresence && $lastPresence->est_actif) {
            return redirect()->back()->with('AddPresenceError', 'La derniere présence est encore active');
        }

        $presence = new Presence;
        $presence->date = date('Y-m-d');
        $presence->est_actif = true;
        $presence->nb_participants = $request->nb_participants;
        $presence->sessionpro_id = $sessionpro->id;
        $presence->save();
        
        return redirect()->route('presences.index', $presence->sessionpro_id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Presence $presence)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Presence $presence)
    {
        return view('presences.edit', compact('presence'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePresenceRequest $request, Presence $presence)
    {
        $presence = Presence::find($presence->id);
        $presence->date = $request->date;
        $presence->nb_participants = $request->nbparticipants;
        $presence->sessionpro_id = $request->sessionpro_id;
        $presence->save();

        return redirect()->route('presences.index', $presence->sessionpro_id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Presence $presence, Sessionpro $sessionpro)
    {
        $presence->delete();
        return redirect()->route('presences.index', $sessionpro);
    }

    public function activate(Presence $presence)
    {
        $presences = Presence::all();

        if($presence->est_actif == false){
            foreach($presences as $p) {
                if($p->est_actif) {
                    return redirect()->back()->with('alredyActivePresence', 'Une présence est déjà active');
                }
            }
            $presence->est_actif = true;
        }
        else {
            $presence->est_actif = false;
        }
        $presence->save();

        return redirect()->back();
    }

    public function participantsPresent(Sessionpro $sessionpro, Presence $presence)
    {
        $participants = $presence->users()->withPivot('num_presence')->orderBy('pivot_num_presence')->get();
        
        return view('presences.participantsPresents', compact('participants','sessionpro','presence'));
    }

    public function deleteParticipantPresent(Presence $presence, User $participant)
    {
        $presence->users()->detach($participant);
        return redirect()->back();
    }
}
