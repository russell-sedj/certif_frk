<?php

namespace App\Http\Controllers;

use App\Models\Presence;
use App\Http\Requests\StorePresenceRequest;
use App\Http\Requests\UpdatePresenceRequest;
use App\Models\Sessionpro;

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
            return redirect()->back()->with('error', 'La derniere présence est encore active');
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
        if($presence->est_actif == false){
            $presence->est_actif = true;
        }
        else {
            $presence->est_actif = false;
        }
        $presence->save();

        return redirect()->back();
    }
}
