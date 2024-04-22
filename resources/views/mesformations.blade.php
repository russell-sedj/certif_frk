@extends('layouts.myapp')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
            <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <span> <h4>Formations suivies</h4></span>
                    </div>
                </div>
                    <table class="table table-responsive">
                        <thead>
                            <tr>
                                <th scope="col">Thème</th>
                                <th scope="col">Période</th>
                                <th scope="col">Lieu</th>
                                <th scope="col">Présence</th>
                                <th scope="col">Note / 20</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                          @foreach($user->sessionpros as $sessionpro)
                            <tr>
                                <td>{{$sessionpro->formation->theme}}</td>
                                <td>{{$sessionpro->date_debut}} à {{$sessionpro->date_fin}}</td>
                                <td>{{$sessionpro->lieu->ville}}@if($sessionpro->lieu->adresse), {{$sessionpro->lieu->adresse}}@endif</td>
                                
                                <td>{{$user->presences->where('sessionpro_id', $sessionpro->id)->count()}}/{{$sessionpro->presences->count()}}</td>
                                <td>{{$sessionpro->users->firstWhere('id', $user->id)->pivot->note}} / 20</td>

                                <td>
                                    <a href="{{route('users.marquerPresenceView', ['sessionpro' => $sessionpro, 'user' => $user])}}" class="btn btn-sm btn-primary">Marquer une présence</a>
                                    @if($sessionpro->peut_demander)
                                    <a href="{{route('demandes.store', [$sessionpro, $user])}}" class="btn btn-sm btn-secondary">Demander l'attestation</a>
                                    @endif
                                    <form action="{{route('inscription', $sessionpro)}}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('PUT')
                                        <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                                    </form>
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


