<?php

namespace App\Http\Controllers;
use App\Models\Demande;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\SessionproUser;
use App\Models\Sessionpro;
use ZipArchive;

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
    $cpt=$demandes->where('prete',false)->count();
    foreach($demandes as $demande){
        $sessionpro=Sessionpro::find($demande->sessionproUser->sessionpro_id);
        $demande['sessionpro']=$sessionpro;
    }
    return view('demandes.agents', compact('demandes','cpt'));
    }
    
    public function indexSuperviseur()
    {   
        $demandes=Demande::all();
        $cpt=$demandes->where('prete',false)->count();
        foreach($demandes as $demande){
            $sessionpro=Sessionpro::find($demande->sessionproUser->sessionpro_id);
            $user=User::find($demande->sessionproUser->user_id);         
            $demande['sessionpro']=$sessionpro;
            $demande['user']=$user;
        }
        return view('demandes.superviseurs', compact('demandes','cpt'));
    }
    public function store(Sessionpro $sessionpro, User $user)
    {   
        $demande=new Demande;
        $sessionproUser=SessionproUser::where([
                 ['sessionpro_id', $sessionpro->id],
                 ['user_id', $user->id ]])->first();
        $demande->sessionpro_user_id=$sessionproUser->id;
        $demande->libelle_demande = "«" . $sessionpro->formation->theme ."»";
        $demande->save();
        session()->flash('toast_message', "Votre demande d'attestation pour le thème <strong>".$demande->libelle_demande."</strong> a été envoyée avec succès!");
        session()->flash('toast_type', 'success');
        return redirect()->route('mesformations');
    }
    public function update (Demande $demandes)
    {   
        foreach($demandes as $demande){
            $demande=Demande::find($demande->id);
            $demande->prete=true;
            $demande->save();
        }
    }
    public function download(Request $request){
        $debut = $request->debut;
        $fin = $request->fin;
        $dgtcp=User::where('titre', "DGTCP")->get();
        $dfpt=User::where('titre', "DFPT")->get();
        $demandes = Demande::whereBetween('created_at', [$debut, $fin])->get();
        foreach($demandes as $demande){
            $sessionpro=Sessionpro::find($demande->sessionproUser->sessionpro_id);
            $user=User::find($demande->sessionproUser->user_id);         
            $demande['sessionpro']=$sessionpro;
            $demande['user']=$user;
        }
         return view('attestation',compact('demandes','dgtcp','dfpt'));
    }
}
