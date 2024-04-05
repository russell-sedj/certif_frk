@extends('layouts.myapp')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h1>Présences</h1>
                    <form action="{{ route('presences.store', $sessionpro) }}" method="post">
                        @csrf
                        <button class="btn btn-success" type="submit">Ajouter une présence</button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
                </div>
                    <table class="table table-responsive">
                        <thead>
                            <tr>
                                <th scope="col">Date</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if($presences)
                        @foreach($presences as $presence)
                            <tr>
                                <td>{{$presence->date}}</td>
                                <td class="d-flex justify-content-between">
                                    <a href="#"class="btn btn-sm btn-primary">Liste de présences</a>

                                    <form action="{{route('presences.activate', $presence)}}" method="post">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-sm {{$presence->est_actif ? 'btn-warning' : 'btn-info'}}">
                                            {{ !$presence->est_actif ? 'Ouvrir présence' : 'Clôturer présence'}}
                                        </button>
                                    </form>
                                    
                                    <form action="{{route('presences.destroy', [$presence->id, $presence->sessionpro])}}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette présence ?')">Supprimer</button>
                                    </form>
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
@endsection


