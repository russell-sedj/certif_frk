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
    })->get()->sortByDesc('created_at');
    $cpt=$demandes->where('statut','envoyée')->count();
    foreach($demandes as $demande){
        $sessionpro=Sessionpro::find($demande->sessionproUser->sessionpro_id);
        $demande['sessionpro']=$sessionpro;
    }
    return view('demandes.agents', compact('demandes','cpt'));
    }
    
public function indexSuperviseur()
{   
    $demandes_sent = Demande::where('statut', "envoyée")->get();
    $demandes_downloaded = Demande::where('statut', "traitement en cours")->get();
    $dates_sent = $this->getMinMaxDates($demandes_sent);
    $dates_downloaded = $this->getMinMaxDates($demandes_downloaded);
    $all_demandes = $demandes_sent->merge($demandes_downloaded);
    foreach ($all_demandes as $demande) {
        $sessionpro = Sessionpro::find($demande->sessionproUser->sessionpro_id);
        $user = User::find($demande->sessionproUser->user_id);         
        $demande['sessionpro'] = $sessionpro;
        $demande['user'] = $user;
    }
    return view('demandes.superviseurs', compact('demandes_sent', 'demandes_downloaded', 'dates_sent', 'dates_downloaded'));
}

private function getMinMaxDates($demandes)
{
    $grouped_dates = $demandes->groupBy(function ($demande) {
        return $demande->created_at->toDateString();
    });

    $dates = $grouped_dates->map(function ($group) {
        return [
            'min' => $group->min('created_at'),
            'max' => $group->max('created_at')
        ];
    });

    return $dates;
}

    public function store(Sessionpro $sessionpro, User $user)
    {   
        $sessionproUser=SessionproUser::where([
                 ['sessionpro_id', $sessionpro->id],
                 ['user_id', $user->id ]])->first();
                 $lastDemande = $sessionproUser->demandes()->latest()->first();
                 if($lastDemande && $lastDemande->statut!="traitée") {
         return redirect()->back()->with('NewDemandeError', "Votre dernière demande d'attestation concernant cette formation est toujours en cours. Veuillez patienter jusqu'à la fin du traitement.");
          }
        $demande=new Demande;
        $demande->sessionpro_user_id=$sessionproUser->id;
        $demande->libelle_demande = "«" . $sessionpro->formation->theme ."»";
        $demande->save();
        session()->flash('toast_message', "Votre demande d'attestation pour le thème <strong>".$demande->libelle_demande."</strong> a été envoyée avec succès!");
        session()->flash('toast_type', 'success');
        return redirect()->route('mesformations');
    }
    public function update (Request $request)
    {   
        $debut = $request->debut;
        $fin = $request->fin;
        $demandes = Demande::where('statut',"envoyée")
                             ->whereBetween('created_at', [$debut, $fin])->get();
        foreach($demandes as $demande){
            $demande=Demande::find($demande->id);
            $demande->statut='traitement en cours';
            $demande->save();
        }
        return redirect()->route('demandesSup.index');
    }
    public function lastupdate (Request $request)
    {   
        $duree = $request->periode;
        if ($duree === 'journee') {
            $jour = $request->jour;
            $debut = date('Y-m-d 00:00:00', strtotime($jour));
            $fin = date('Y-m-d 23:59:59', strtotime($jour));
        }
        else
        {
            $debut = $request->debut;
            $fin = $request->fin;
        }
        $demandes = Demande::where('statut',"traitement en cours")
                            ->whereBetween('created_at', [$debut, $fin])->get();
        foreach($demandes as $demande){
            $demande=Demande::find($demande->id);
            $demande->statut='traitée';
            $demande->save();
        }
        
            $toastMessage = "Les réponses aux demandes de la journée du <strong>" .
            \Carbon\Carbon::parse($debut)->locale('fr')->isoFormat('D MMMM YYYY')
            . "</strong>";
            if ($duree !== 'journee') {
                $toastMessage .= " au <strong>" . 
                \Carbon\Carbon::parse($fin)->locale('fr')->isoFormat('D MMMM YYYY')
                . "</strong>";
            }          
            $toastMessage .= " ont été envoyées avec succès!";       
            session()->flash('toast_message', $toastMessage);
            session()->flash('toast_type', 'success');
        
        return redirect()->route('demandesSup.index');
    }
    public function download(Request $request){
        $duree = $request->periode;
        if ($duree === 'journee') {
            $jour = $request->jour;
            $debut = date('Y-m-d 00:00:00', strtotime($jour));
            $fin = date('Y-m-d 23:59:59', strtotime($jour));
        }
        else{
            $debut = $request->debut;
            $fin = $request->fin;
        }
        $dgtcp=User::where('titre', "DGTCP")->first();
        $dfpt=User::where('titre', "DFPT")->first();
        $demandes = Demande::where('statut',"envoyée")
                             ->whereBetween('created_at', [$debut, $fin])
                             ->get();
        foreach($demandes as $demande){
            $sessionpro=Sessionpro::find($demande->sessionproUser->sessionpro_id);
            $user=User::find($demande->sessionproUser->user_id);         
            $demande['sessionpro']=$sessionpro;
            $demande['user']=$user;
        }
         return view('attestation',compact('demandes','dgtcp','dfpt','debut','fin'));
    }
}
