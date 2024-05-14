@extends('layouts.myapp')
@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
            <div class="card-header">
            <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('formations')}}">Catégories</a></li>
                        <li class="breadcrumb-item"><a href="{{route('formations.index', $formation->categorie)}}">Formations</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Sessions</li>
                    </ol>
                </nav>
                <h5>
                <div class="d-flex justify-content-between align-items-center">
                    Liste des sessions
                    @if(in_array(auth()->user()->role, ['admin','superviseur']))
                    <a href="{{route('sessionpros.create',$formation)}}" class="btn btn-success">Ajouter une session</a>
                    @endif
                </div>
                </h5>
            </div>
            <div class="card-body">
             </div>
                @if (session('AddSessionError'))
                    <div class="alert alert-danger">
                        {{ session('AddSessionError') }}
                    </div>
                @endif
                @if (session('PeutInscrireError'))
                    <div class="alert alert-danger">
                        {{ session('PeutInscrireError') }}
                    </div>
                @endif
                @if (session('updateError'))
                    <div class="alert alert-danger">
                        {{ session('updateError') }}
                    </div>
                @endif
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Période</th>
                                <th scope="col">Horaires</th>
                                <th scope="col">Lieu</th>
                                @if(in_array(auth()->user()->role, ['admin','superviseur']))
                                <th scope="col">Code</th>
                                @endif
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if($sessionpros)
                        @foreach($sessionpros as $sessionpro)
                            <tr>
                                <td>@if($sessionpro->date_debut==$sessionpro->date_fin)
                                Journée du {{ \Carbon\Carbon::parse($sessionpro->date_debut)->locale('fr')->isoFormat('D MMMM YYYY')}} @else
                                {{ \Carbon\Carbon::parse($sessionpro->date_debut)->locale('fr')->isoFormat('D MMMM')}}
                                 au {{\Carbon\Carbon::parse($sessionpro->date_fin)->locale('fr')->isoFormat('D MMMM YYYY')}} @endif</td>
                                <td>{{date('H:i', strtotime($sessionpro->heure_debut))}} à {{date('H:i',strtotime($sessionpro->heure_fin))}}</td>
                                <td>
                                @if($sessionpro->lieu->adresse){{$sessionpro->lieu->adresse}}, @endif {{$sessionpro->lieu->ville}}                                   
                                </td>
                                @if(in_array(auth()->user()->role, ['admin','superviseur']))
                                <td>
                                    {{$sessionpro->code}}
                                </td>
                                @endif
                                <td>
                                @if(auth()->user()->role === 'participant')
                                    @if($sessionpro->peut_inscrire)
                                    <form action="{{route('inscription', $sessionpro)}}" method="post">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="participant" value="{{$user->id}}">
                                        <button type="submit" id="{{$user->sessionpros->contains($sessionpro->id)? 'desinscrire'.$sessionpro->id: 'inscrire'.$sessionpro->id}}"
                                        class="btn btn-sm mb-2 {{$user->sessionpros->contains($sessionpro->id)? 'btn-warning' : 'btn-info'}}" data-session="{{$sessionpro->id}}"
                                        onclick="{{ $user->sessionpros->contains($sessionpro->id) ? 'return confirm(\'Êtes-vous sûr de vouloir vous désinscrire de cette session?\')' : '' }}">
                                        {{ !$user->sessionpros->contains($sessionpro->id)? "S'inscrire" : "Se désinscrire"}}
                                        </button>
                                    </form>
                                    @endif
                                @endif    
                                @if(in_array(auth()->user()->role, ['admin','superviseur']))
                                    <a href="{{route('sessionpros.edit', $sessionpro)}}" class="btn btn-sm btn-warning mb-2"
                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Modifier"><i class="bi bi-pencil-fill"></i></a>
                                    
                                    <form action="{{route('sessionpros.destroy', [$sessionpro->id, $sessionpro->formation])}}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger mb-2" onclick="return confirm('La suppression de cette session entraînera également celle de toutes autres données qui y sont liées. Êtes-vous sûr de vouloir continuer?')"
                                data-bs-toggle="tooltip" data-bs-placement="top"title="Supprimer"> <i class="bi bi-trash3-fill"></i></button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        @else
                            <tr>
                                <td colspan="5">Aucune session trouvée.</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Fenêtre modale -->
<div class="modal" id="inscriptionModal" tabindex="-1" aria-labelledby="inscriptionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="inscriptionModalLabel">Inscription</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="inscriptionForm">
          <div class="mb-3">
            <label for="code" class="form-label">Entrez le code :</label>
            <input type="text" class="form-control" id="code" name="code" required>
          </div>
          <input type="hidden" id="participant" name="participant" value="{{$user->id}}">
          <input type="hidden" class="form-control" id="session" name="session" required>
          <button type="submit" class="btn btn-primary">Valider</button>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Inclure les fichiers CSS et JavaScript de SweetAlert -->


<script>
document.addEventListener('DOMContentLoaded', function() {
    // Afficher la fenêtre modale lors du clic sur le bouton "S'inscrire"
    document.querySelectorAll('.btn-info').forEach(function(button) {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            var session = button.getAttribute('data-session');
            document.getElementById('session').value = session;
            var modal = new bootstrap.Modal(document.getElementById('inscriptionModal'));
            modal.show();
        });
    });

    // Gérer la soumission du formulaire d'inscription
    document.getElementById('inscriptionForm').addEventListener('submit', function(event) {
        event.preventDefault();
        var code = document.getElementById('code').value;
        var sessionpro = document.getElementById('session').value;
        var participant=document.getElementById('participant').value;
        // Utilisation de la méthode PUT pour l'inscription
        fetch('{{ route('inscription', ['sessionpro' => ':sessionpro']) }}'.replace(':sessionpro', sessionpro), {
            method: 'PUT',
            body: JSON.stringify({ code: code , participant: participant}),
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Ajout du jeton CSRF pour la sécurité
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Afficher une alerte SweetAlert de succès
                Swal.fire({
                    icon: 'success',
                    title: 'Inscription validée !',
                    showConfirmButton: false,
                    timer: 1500 // Fermer automatiquement l'alerte après 1,5 seconde
                }).then(() => {
                    // Recharger la page pour mettre à jour les données
                    location.reload();
                });
            } else {
                // Afficher une alerte SweetAlert d'échec
                Swal.fire({
                    icon: 'error',
                    title: 'Code invalide. Veuillez réessayer.',
                    showConfirmButton: false,
                    timer: 3000 // Fermer automatiquement l'alerte après 3 secondes
                });
            }
        })
        .catch(error => {
            console.error('Erreur lors de la validation du code :', error);
            // Afficher un message d'erreur générique
            alert('Une erreur est survenue. Veuillez réessayer.');
        });
    });
});
</script>

@endsection


