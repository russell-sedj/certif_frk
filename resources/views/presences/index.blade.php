@extends('layouts.myapp')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
            <div class="card-header">
            <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('sessionpros.dashboard')}}">Tableau de bord</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Présences</li>
                    </ol>
                </nav>
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title">
                     Présences
                    </h3> 
                    <form action="{{ route('presences.store', $sessionpro) }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="nb_participants">Nombre de Participants: </label>
                            <input type="number" name="nb_participants" min="0" class="form-control" required>
                        </div>
                        <button class="btn btn-success" type="submit">Ajouter une présence</button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                @if (session('addPresenceError'))
                    <div class="alert alert-danger">
                        {{ session('addPresenceError') }}
                    </div>
                @endif
                @if (session('alredyActivePresence'))
                    <div class="alert alert-danger">
                        {{ session('alredyActivePresence') }}
                    </div>
                @endif
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col" style="text-align:center;">Date</th>
                                <th scope="col" style="text-align:center;">Créée à</th>
                                <th scope="col" style="text-align:center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if($presences)
                        @foreach($presences as $presence)
                            <tr>
                                <td style="text-align:center;">
                                {{\Carbon\Carbon::parse($presence->date)->locale('fr')->isoFormat('D MMMM YYYY')}}
                               </td>
                               <td style="text-align:center;">
                               {{date('H:i', strtotime($presence->created_at))}}
                               </td>
                                <td style="display:flex; justify-content:center">  
                                <div class="btn-group btn-group-space" role="group" aria-label="Actions">
                              <a href="{{route('presences.participantsPresent', ['sessionpro'=>$sessionpro, 'presence'=>$presence])}}"class="btn btn-sm btn-primary"data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Participants présents"><i class="bi bi-people-fill"></i></a>

                                    <form action="{{route('presences.activate', $presence)}}" method="post">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-sm {{$presence->est_actif ? 'btn-warning' : 'btn-info'}}"data-bs-toggle="tooltip" data-bs-placement="top"
                                title="{{$presence->est_actif ? 'Clôturer la présence' : 'Ouvrir la présence'}}">
                                            @if(!$presence->est_actif)<i class="bi bi-person-plus-fill"></i>@else<i class="bi bi-person-fill-lock"></i>@endif
                                        </button>
                                    </form>
                                    
                                    <form action="{{route('presences.destroy', [$presence->id, $presence->sessionpro])}}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir  supprimer cette présence ?')" data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Supprimer"> <i class="bi bi-trash3-fill"></i>
                                      </button>
                                    </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        @else
                            <tr>
                                <td colspan="5">Aucune présence faite</td>
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
@endsection
<style>
    .btn-group-space .btn {
    margin: 0 5px; 
    height:30px;
}
</style>
