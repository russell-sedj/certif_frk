@extends('layouts.myapp')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
            <div class="card-header">
                <h1 class="mb-4 fs-2">Thème: {{ $theme }}</h1>
                <div class="d-flex justify-content-between align-items-center">
                    <h3>Liste des sessions du thème</h3>
                    <a href="{{route('sessionpros.create',$formation)}}" class="btn btn-success">Ajouter une session</a>
                </div>
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
                    <table class="table table-responsive">
                        <thead>
                            <tr>
                                <th scope="col">Periode</th>
                                <th scope="col">Horaire</th>
                                <th scope="col">Lieu</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if($sessionpros)
                        @foreach($sessionpros as $sessionpro)
                            <tr>
                                <td>{{$sessionpro->date_debut}} à {{$sessionpro->date_fin}}</td>
                                <td>{{$sessionpro->heure_debut}} à {{$sessionpro->heure_fin}}</td>
                                <td>
                                        {{$sessionpro->lieu->ville}}@if($sessionpro->lieu->adresse), {{$sessionpro->lieu->adresse}}@endif                                    
                                </td>
                                <td>
                                    <a href="{{ route('presences.index', $sessionpro) }}" class="btn btn-sm btn-primary mb-2 mt-2">Voir les presences</a>
                                    
                                    <form action="{{route('sessionpros.actionInscription', $sessionpro)}}" method="post">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-sm mb-2 {{$sessionpro->peut_inscrire ? 'btn-warning' : 'btn-success'}}">
                                            {{ !$sessionpro->peut_inscrire ? 'Ouvrir inscription' : 'Cloturer inscription'}}
                                        </button>
                                    </form>
                                    @if($sessionpro->peut_inscrire)
                                    <form action="{{route('inscription', $sessionpro)}}" method="post">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-sm mb-2 {{$user->sessionpros->contains($sessionpro->id)? 'btn-warning' : 'btn-info'}}">
                                            {{ !$user->sessionpros->contains($sessionpro->id)? "S'inscrire" : "Se désinscrire"}}
                                        </button>
                                    </form>
                                    @endif
                                    @if(!$sessionpro->peut_demander)
                                        <form action="{{route('sessionpros.openRequest', $sessionpro)}}" method="post">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-sm btn-success mb-2"  
                                            onclick="return confirm('Vous ne pourrez plus modifer cette valeur, etes vous sur ?')" >
                                                Autoriser demande
                                            </button>
                                        </form>
                                    @endif
                                    
                                    <a href="{{route('sessionpros.edit', $sessionpro)}}" class="btn btn-sm btn-secondary mb-2">Modifier</a>
                                    
                                    <form action="{{route('sessionpros.destroy', [$sessionpro->id, $sessionpro->formation])}}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger mb-2" onclick="return confirm('La suppression de cette session entraînera également celle de toutes autres données qui y sont liées. Êtes-vous sûr de vouloir continuer?')">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        @else
                            <tr>
                                <td colspan="5">Aucune session trouvée</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


