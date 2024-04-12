@extends('layouts.myapp')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h1 class="mb-4 fs-2">Thème: {{ $sessionpro->formation->theme }}</h1>
                    <h4 class="mb-4">Lieu: {{$sessionpro->lieu->ville}}@if($sessionpro->lieu->adresse), {{$sessionpro->lieu->adresse}}@endif</h4>
                    <h4 class="mb-4">Date: {{$presence->date}}</h4>
                    <div class="d-flex justify-content-between align-items-center">
                        <h3>Liste des participants présents</h3>
                    </div>
                </div>
                    <table class="table table-responsive">
                        <thead>
                            <tr>
                                <th scope="col">Numero</th>
                                <th scope="col">Participant</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if($participants)
                        @foreach($participants as $participant)
                            <tr>
                                <td>{{$participant->presences->firstWhere('id', $presence->id)->pivot->num_presence}}</td>
                                <td>{{$participant['name']}} {{$participant['surname']}}</td>
                                <td>
                                    <form action="{{route('presences.deleteParticipantPresent', [$presence, $participant])}}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir retirer ce participant de la présence ?')">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        @else
                            <tr>
                                <td colspan="5">Aucun participant présent</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection