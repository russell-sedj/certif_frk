<?php

namespace App\Http\Controllers;
use App\Models\Demande;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\SessionproUser;
use App\Models\Sessionpro;
class DemandeController extends Controller
{
    //
    public function indexAgent(Request $request)
    {
     $user=$request->user();
     $id=$user->id;
     $demandes = Demande::whereIn('sessionpro_user_id', function ($query) use ($id) {
        $query->select('id')
            ->from('sessionpro_user')
            ->where('user_id', $id);
    })->get();
    return view('demandes.agents', compact('demandes'));
    }
    
    public function indexSuperviseur()
    {   
        $demandes=Demande::all();
        return view('demandes.superviseurs', compact('demandes'));
    }

    public function store(Sessionpro $sessionpro, User $user)
    {   
        $demande=new Demande;
        $sessionproUser=SessionproUser::where([
                 ['sessionpro_id', $sessionpro->id],
                 ['user_id', $user->id ]])->first();
        $demande->sessionpro_user_id=$sessionproUser->id;
        $demande->libelle_demande= "Nouvelle demande d'attestation de"." ".$user->name." ".$user->surname.".";
        $demande->theme=$sessionpro->formation->theme;
        $demande->save();
        return redirect()->route('mesformations');
    }
    public function update ()
    {   
        
    }
}
