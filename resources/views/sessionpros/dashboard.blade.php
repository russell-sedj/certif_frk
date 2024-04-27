@extends('layouts.myapp')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h1>Liste des sessions</h1>
                </div>
                <div class="d-flex justify-content-between mt-4">
                    <form action="{{route('sessionpros.dashboard')}}" method="get">
                        @csrf
                        <input type="text" name="category" placeholder="Entrer la catégorie"><br>
                        <button type="submit" class="btn btn-primary mt-2">Rechercher par categorie</button>
                    </form>
                    <form action="{{route('sessionpros.dashboard')}}" method="get">
                        @csrf
                        <input type="text" name="formation" placeholder="Entrer la formation"><br>
                        <button type="submit" class="btn btn-primary mt-2">Rechercher par formation</button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                @if (session('PeutInscrireError'))
                    <div class="alert alert-danger">
                        {{ session('PeutInscrireError') }}
                    </div>
                @endif
                @if (session('openRequestError'))
                    <div class="alert alert-danger">
                        {{ session('openRequestError') }}
                    </div>
                @endif
            </div>
                    <table class="table table-responsive">
                        <thead>
                            <tr>
                                <th scope="col">Periode</th>
                                <th scope="col">Nombre de présences</th>
                                <th scope="col">Lieu</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if($sessionpros)
                        @foreach($sessionpros as $sessionpro)
                            <tr>
                                <td>{{ $sessionpro->date_debut}} à {{$sessionpro->date_fin }}</td>

                                <td class="text-center">{{ $sessionpro->presences->count() }}</td>

                                <td>
                                    {{$sessionpro->lieu->ville}}@if($sessionpro->lieu->adresse), {{$sessionpro->lieu->adresse}}@endif                                    
                                </td>
                                
                                <td>
                                    <a href="{{ route('presences.index', $sessionpro) }}" class="btn btn-sm btn-primary mb-2 mt-2">Voir les présences</a>
                                    
                                    <a href="{{ route('sessionpros.participants', $sessionpro) }}" class="btn btn-sm btn-info mb-2 mt-2">Liste des participants</a>
                                    
                                    <form action="{{route('sessionpros.actionInscription', $sessionpro)}}" method="post">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-sm mb-2 {{$sessionpro->peut_inscrire ? 'btn-warning' : 'btn-success'}}">
                                            {{ !$sessionpro->peut_inscrire ? 'Ouvrir inscription' : 'Cloturer inscription'}}
                                        </button>
                                    </form>
                                    
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


