@extends('layouts.myapp')
@section('content')

<link rel="stylesheet" href="//cdn.datatables.net/2.0.5/css/dataTables.dataTables.min.css">

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h1>Liste des sessions</h1>
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
            <table class="table table-responsive text-sm" id="myTable">
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
                                    
                                    <span hidden>{{$sessionpro->formation->theme}}</span>
                                    <span hidden>{{$sessionpro->formation->categorie->nom}}</span>
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

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="//cdn.datatables.net/2.0.5/js/dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#myTable').DataTable();
    });
</script>
@endsection


