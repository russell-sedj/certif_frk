<?php

namespace App\Http\Controllers;

use App\Models\Formation;
use App\Http\Requests\StoreFormationRequest;
use App\Http\Requests\UpdateFormationRequest;
use App\Models\Categorie;
use App\Models\Lieu;
use App\Models\Sessionpro;

class FormationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Categorie $categorie)
    {   
        $formations=$categorie->formations;
        return view('formations.index', compact('formations','categorie'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Categorie $categorie)
    {
        //
        $lieus = Lieu::all();
        return view('formations.create', compact('categorie','lieus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFormationRequest $request)
    {
        //
        $formation = new Formation;
        $formation->theme = $request->theme;
        $formation->description = $request->description;
        $formation->categorie_id = $request->categorie_id;
        $formation->save();
        
        $nombre_sessions = $request->nombre_sessions;
        
        for ($i = 0; $i < $nombre_sessions; $i++) {
            $sessionpro = new Sessionpro;
            $sessionpro->date_debut = $request->input("date_debut_$i");
            $sessionpro->date_fin = $request->input("date_fin_$i");
            $sessionpro->heure_debut = $request->input("heure_debut_$i");
            $sessionpro->heure_fin = $request->input("heure_fin_$i");
            $sessionpro->lieu_id = $request->input("lieu_$i");
            $sessionpro->formation_id = $formation->id;
            $sessionpro->save();
        }
        
        
        return redirect()->route('formations.index', $formation->categorie_id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Formation $formation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Formation $formation)
    {
        //
        return view('formations.edit', compact('formation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFormationRequest $request, Formation $formation)
    {
        //
        $formation=Formation::find($formation->id);
        $formation->theme=$request->theme;
        $formation->description=$request->description;
        $formation->categorie_id=$request->categorie_id;
        $formation->save();
        return redirect()->route('formations.index', $formation->categorie_id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Formation $formation, Categorie $categorie)
    {
        //
        $formation->delete();
        return redirect()->route('formations.index', $categorie);

    }
}
