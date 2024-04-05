@extends('layouts.myapp')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
            <div class="card-header">
                {{-- <h1 class="mb-4 fs-2">Theme: {{ $theme }}</h1> --}}
                <div class="d-flex justify-content-between align-items-center">
                    <h1>Liste des sessions</h1>
                    {{-- <a href="{{route('sessionpros.store',$formation)}}" class="btn btn-success">Ajouter une session</a> --}}
                </div>
            </div>
            <div class="card-body">
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
                            <tr class="cursor-pointer" onclick="window.location='{{ route('sessionpros.show', $sessionpro) }}'">
                                <td>{{ $sessionpro->date_debut}} - {{$sessionpro->date_fin }}</td>

                                <td>{{ $sessionpro->presences->count() }}</td>

                                <td>
                                        {{$sessionpro->lieu->ville}}@if($sessionpro->lieu->adresse), {{$sessionpro->lieu->adresse}}@endif                                    
                                </td>
                                
                                <td>
                                    <a href="{{ route('presences.index', $sessionpro) }}" class="btn btn-sm btn-primary mb-2 mt-2">Voir</a>
                                    
                                    <a href="{{ route('sessionpros.participants', $sessionpro) }}" class="btn btn-sm btn-info mb-2 mt-2">Liste des participants</a>
                                    
                                    <form action="{{route('sessionpros.actionInscription', $sessionpro)}}" method="post">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-sm mb-2 {{$sessionpro->peut_inscrire ? 'btn-warning' : 'btn-info'}}">
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
                                    
                                    {{-- <form action="{{route('sessionpros.destroy', [$sessionpro->id, $sessionpro->formation])}}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger mb-2" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette session ?')">Supprimer</button>
                                    </form> --}}
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


