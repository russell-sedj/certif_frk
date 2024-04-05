<?php

namespace App\Http\Controllers;
use App\Models\Demande;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\SessionproUser;
class DemandeController extends Controller
{
    //
    public function indexAgent(User $user)
    {   
     $sessionproUsers=SessionproUser::where('user_id', $user)->get();    
    return view('demandes.agents', compact('sessionproUsers'));
    }
    
    public function indexSuperviseur()
    {   
        $demandes=Demande::all();
        return view('demandes.superviseurs', compact('demandes'));
    }

    public function store(SessionproUser $sessionproUser)
    {   
        $demande=new Demande;
        $demande->sessionpro_user_id=$sessionproUser->id;
        $demande->libelle_demande="Demande d'attestation";
    }
    public function update ()
    {   
        
    }
}
