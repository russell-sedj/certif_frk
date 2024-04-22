<?php

namespace App\Http\Controllers;

use App\Models\Sessionpro;
use App\Http\Requests\StoreSessionproRequest;
use App\Http\Requests\UpdateSessionproRequest;
use App\Models\Categorie;
use App\Models\Formation;
use App\Models\Lieu;
use Illuminate\Http\Request;
class SessionproController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Formation $formation, Request $request)
    {
        $sessionpros = $formation->sessionpros;
        $theme = $formation->theme;
        // $lieus=Lieu::all();
        $user=$request->user();
        return view('sessionpros.index', compact('sessionpros', 'formation', 'user', 'theme'));
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
        /*if ($request->has('peut_inscrire')) {
            $sessionpro->peut_inscrire = $request->peut_inscrire;
        }
        if ($request->has('peut_demander')) {
            $sessionpro->peut_demander = $request->peut_demander;
        }*/
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

    public function actionInscription(Sessionpro $sessionpro)
    {
        //$sessionpro = Sessionpro::find($sessionpro->id);
        if($sessionpro->peut_inscrire == false){
            $sessionpro->peut_inscrire = true;
        }
        else {
            $sessionpro->peut_inscrire = false;
        }
        $sessionpro->save();

        return redirect()->back();
        // return redirect()->route('sessionpros.index', $sessionpro->formation_id);
    }
    
    public function openRequest(Sessionpro $sessionpro)
    {
        $sessionpro->peut_demander = true;
        $sessionpro-> save();

        return redirect()->back();
        // return redirect()->route('sessionpros.index', $sessionpro->formation_id);
    }

    public function dashboard(Request $request)
    {
        $sessionpros = collect();
        
        if ($request->has('category') && $request->category != '') {
            $categories = Categorie::where('nom', $request->category)->get();

            foreach ($categories as $categorie) {
                $formations = $categorie->formations;
                foreach ($formations as $formation) {
                    $sessionpros = $sessionpros->concat($formation->sessionpros);
                }
            }
        }
        else if ($request->has('formation') && $request->formation != '') {
            $formations = Formation::where('theme', $request->formation)->get();

            foreach ($formations as $formation) {
                $sessionpros = $sessionpros->concat($formation->sessionpros);
            }
        }
        else {
            $sessionpros = Sessionpro::all();
        }

        return view('sessionpros.dashboard', compact('sessionpros'));
    }

    public function participants(Sessionpro $sessionpro)
    {
        $users = $sessionpro->users;
        $theme = $sessionpro->formation->theme;

        return view('sessionpros.participants', compact('sessionpro', 'users', 'theme'));
    }

    public function noterParticipant(Sessionpro $sessionpro, $user)
    {
        $sessionpro->users()->updateExistingPivot($user, ['note' => request('note')]);

        return redirect()->back();
    }
}