@extends('layouts.myapp')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
            <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                    <h1>Tableau de bord</h1>
                </div>
                </div>
                <div class="card-body">
                @if (session('NewDemandeError'))
                    <div class="alert alert-danger">
                        {{ session('NewDemandeError') }}
                    </div>
                @endif
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">                       
                         <thead>
                            <tr>
                                <th scope="col" style="text-align:center;">Thème</th>
                                <th scope="col" style="text-align:center;">Période</th>
                                <th scope="col" style="text-align:center;">Lieu</th>
                                <th scope="col" style="text-align:center;">Présence</th>
                                <th scope="col" style="text-align:center;">Note / 20</th>
                                <th scope="col" style="text-align:center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                          @foreach($user->sessionpros->sortByDesc('created_at') as $sessionpro)
                            <tr>
                                <td style="text-align:center;">{{$sessionpro->formation->theme}}</td>
                                <td style="text-align:center;">@if($sessionpro->date_debut==$sessionpro->date_fin)
                                {{ \Carbon\Carbon::parse($sessionpro->date_debut)->locale('fr')->isoFormat('D MMMM YYYY')}} @else
                                {{ \Carbon\Carbon::parse($sessionpro->date_debut)->locale('fr')->isoFormat('D MMMM')}}
                                 au {{\Carbon\Carbon::parse($sessionpro->date_fin)->locale('fr')->isoFormat('D MMMM YYYY')}} @endif</td>
                                 <td style="text-align:center;">
                                @if($sessionpro->lieu->adresse){{$sessionpro->lieu->adresse}}, @endif {{$sessionpro->lieu->ville}}                                   
                                </td>                                
                                <td style="text-align:center;">{{$user->presences->where('sessionpro_id', $sessionpro->id)->count()}}/{{$sessionpro->presences->count()}}</td>
                                <td style="text-align:center;">{{$sessionpro->users->firstWhere('id', $user->id)->pivot->note}}</td>

                                <td style="text-align:center;">
                                    <a href="{{route('users.marquerPresenceView', ['sessionpro' => $sessionpro, 'user' => $user])}}" class="btn btn-sm btn-primary"
                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                    title="Marquer une présence"><i class="bi bi-person-check-fill"></i></a>
                                    @if($sessionpro->peut_demander &&
                                    $user->presences->where('sessionpro_id', $sessionpro->id)->count()==$sessionpro->presences->count() &&
                                    $sessionpro->users->firstWhere('id', $user->id)->pivot->note>=10
                                    )
                                    <a href="{{route('demandes.store', [$sessionpro, $user])}}" class="btn btn-sm btn-secondary" data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="Demander l'attestation">
                                        <i class="bi bi-mortarboard-fill"></i></a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@if (session('toast_message'))
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false" id="myToast">
        <div class="toast-header">
            <svg class="bd-placeholder-img rounded me-2" width="20" height="20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" preserveAspectRatio="xMidYMid slice" focusable="false"><rect width="100%" height="100%" fill="#007aff"></rect></svg>
            <strong class="me-auto">Notification</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            <a href="{{ route('demandesAgent.index')}}" class="toast-link">{!!session('toast_message') !!}</a>
        </div>
    </div>
</div>
<script>
    // Afficher le toast lorsque la page est chargée
    document.addEventListener('DOMContentLoaded', function() {
        var myToast = new bootstrap.Toast(document.getElementById('myToast'));
        myToast.show();

        // Rediriger vers la route demandesAgent.index lors du clic sur le toast
        var toastLink = document.querySelector('.toast-link');
        toastLink.addEventListener('click', function(event) {
            event.preventDefault(); // Empêcher le comportement par défaut du lien
            window.location.href = this.href; // Rediriger vers l'URL du lien
        });
    });
</script>
@endif
@endsection


