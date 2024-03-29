<?php

namespace App\Http\Controllers;

use App\Models\Sessionpro;
use App\Http\Requests\StoreSessionproRequest;
use App\Http\Requests\UpdateSessionproRequest;
use App\Models\Formation;
use App\Models\Lieu;

class SessionproController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Formation $formation)
    {
        $sessionpros = $formation->sessionpros;
        $lieus=Lieu::all();

        return view('sessionpros.index', compact('sessionpros', 'formation', 'lieus'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($formation)
    {
        $lieus = Lieu::all();

        return view('sessionpros.create', compact('formation', 'lieus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSessionproRequest $request)
    {
        $sessionpro = new Sessionpro;
        $sessionpro->date_debut = $request->date_debut;
        $sessionpro->date_fin = $request->date_fin;
        $sessionpro->heure_debut = $request->heure_debut;
        $sessionpro->heure_fin = $request->heure_fin;
        $sessionpro->lieu_id = $request->lieu_id;
        $sessionpro->formation_id = $request->formation_id;
        $sessionpro->save();
        
        return redirect()->route('sessionpros.index', $sessionpro->formation_id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Sessionpro $sessionpro)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sessionpro $sessionpro)
    {
        $lieus = Lieu::all();
        
        return view('sessionpros.edit', compact('sessionpro', 'lieus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSessionproRequest $request, Sessionpro $sessionpro)
    {
        $sessionpro = Sessionpro::find($sessionpro->id);
        $sessionpro->date_debut = $request->date_debut;
        $sessionpro->date_fin = $request->date_fin;
        $sessionpro->heure_debut = $request->heure_debut;
        $sessionpro->heure_fin = $request->heure_fin;
        if ($request->has('peut_inscrire')) {
            $sessionpro->peut_inscrire = $request->peut_inscrire;
        }
        if ($request->has('peut_demander')) {
            $sessionpro->peut_demander = $request->peut_demander;
        }
        $sessionpro->lieu_id = $request->lieu_id;
        $sessionpro->formation_id = $request->formation_id;
        $sessionpro->save();

        return redirect()->route('sessionpros.index', $sessionpro->formation_id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sessionpro $sessionpro, Formation $formation)
    {
        $sessionpro->delete();
        return redirect()->route('sessionpros.index', $formation);
    }
}
