@extends('layouts.myapp')
@section('content')
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
                    <a href="{{route('sessionpros.create',$formation)}}" class="btn btn-success">Ajouter une session</a>
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
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if($sessionpros)
                        @foreach($sessionpros as $sessionpro)
                            <tr>
                                <td>@if($sessionpro->date_debut==$sessionpro->date_fin)
                                {{ \Carbon\Carbon::parse($sessionpro->date_debut)->locale('fr')->isoFormat('D MMMM YYYY')}} @else
                                {{ \Carbon\Carbon::parse($sessionpro->date_debut)->locale('fr')->isoFormat('D MMMM')}}
                                 au {{\Carbon\Carbon::parse($sessionpro->date_fin)->locale('fr')->isoFormat('D MMMM YYYY')}} @endif</td>
                                <td>{{date('H:i', strtotime($sessionpro->heure_debut))}} à {{date('H:i',strtotime($sessionpro->heure_fin))}}</td>
                                <td>
                                @if($sessionpro->lieu->adresse){{$sessionpro->lieu->adresse}}, @endif {{$sessionpro->lieu->ville}}                                   
                                </td>
                                <td>
                                @if(auth()->user()->role === 'aucun')
                                    @if($sessionpro->peut_inscrire)
                                    <form action="{{route('inscription', $sessionpro)}}" method="post">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-sm mb-2 {{$user->sessionpros->contains($sessionpro->id)? 'btn-warning' : 'btn-info'}}">
                                            {{ !$user->sessionpros->contains($sessionpro->id)? "S'inscrire" : "Se désinscrire"}}
                                        </button>
                                    </form>
                                    @endif
                                @endif    
                                @if(in_array(auth()->user()->role, ['admin','superviseur']))
                                    <a href="{{route('sessionpros.edit', $sessionpro)}}" class="btn btn-sm btn-warning mb-2"><i class="bi bi-pencil-fill"></i></a>
                                    
                                    <form action="{{route('sessionpros.destroy', [$sessionpro->id, $sessionpro->formation])}}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger mb-2" onclick="return confirm('La suppression de cette session entraînera également celle de toutes autres données qui y sont liées. Êtes-vous sûr de vouloir continuer?')">                            <i class="bi bi-trash3-fill"></i>
</button>
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
@endsection


