<?php

namespace App\Http\Controllers;

use App\Models\Formation;
use App\Http\Requests\StoreFormationRequest;
use App\Http\Requests\UpdateFormationRequest;
use App\Models\Categorie;
use App\Models\Lieu;
use App\Models\Sessionpro;
use Illuminate\Support\Facades\Validator;
class FormationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Categorie $categorie)
    {   
        $formations=$categorie->formations->sortByDesc('created_at');
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
    $nombre_sessions = $request->nombre_sessions;
    if ($nombre_sessions > 0 && Lieu::count() === 0) {
        return back()->with('AddSessionError', 'Aucun lieu enregistré. Veuillez d\'abord ajouter un lieu.');
    }
    $errors = [];
    for ($i = 0; $i < $nombre_sessions; $i++) {     
        $validator = Validator::make($request->all(), [
            'date_debut_'.$i => ['required', 'date'],
            'date_fin_'.$i => ['required', 'date', 'after_or_equal:date_debut_'.$i],
            'heure_debut_'.$i => ['required', 'date_format:H:i'],
            'heure_fin_'.$i => ['required', 'date_format:H:i', 'after:heure_debut_'.$i],
            'lieu_'.$i => ['required'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $errors["date_debut_$i"][] = $error;
                $errors["date_fin_$i"][] = $error;
                $errors["heure_debut_$i"][] = $error;
                $errors["heure_fin_$i"][] = $error;
                $errors["lieu_$i"][] = $error;
            }        }
    }

    // Vérification des erreurs
    if (!empty($errors)) {
        return back()->withInput()->withErrors($errors)->with('nombre_sessions', $nombre_sessions);
    }

    // Création de la formation
    $formation = new Formation;
    $formation->theme = $request->theme;
    $formation->description = $request->description;
    $formation->categorie_id = $request->categorie_id;
    $formation->save();

    // Création des sessions
    for ($i = 0; $i < $nombre_sessions; $i++) {
        $sessionpro = Sessionpro::create([
            'date_debut' => $request->input("date_debut_".$i),
            'date_fin' => $request->input("date_fin_".$i),
            'heure_debut' => $request->input("heure_debut_".$i),
            'heure_fin' => $request->input("heure_fin_".$i),
            'lieu_id' => $request->input("lieu_".$i),
            'formation_id' => $formation->id,
        ]);
        $sessionpro->code=date('is', strtotime($sessionpro->created_at)) . '-' . date('i', strtotime($sessionpro->formation->created_at));
        $sessionpro->save();
    }
    
    // Redirection vers l'index des formations de la catégorie
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
