@extends('layouts.myapp')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h1 class="mb-4 fs-2">Thème: {{ $theme }}</h1>
                    <h4 class="mb-4">Lieu: {{$sessionpro->lieu->ville}}@if($sessionpro->lieu->adresse), {{$sessionpro->lieu->adresse}}@endif</h4>
                    <div class="d-flex justify-content-between align-items-center mt-5">
                        <h3>Liste des participants à la session</h3>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-responsive">
                        <thead>
                            <tr>
                                <th scope="col">Nom et Prénoms</th>
                                <th scope="col">Nombre de présences</th>
                                <th scope="col">Note /20</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if($users)
                        @foreach($users as $user)
                            <tr>
                                <td>{{$user->name}} {{$user->surname}}</td>
                                <td>{{$user->presences->where('sessionpro_id', $sessionpro->id)->count()}}/{{$sessionpro->presences->count()}}</td>
                                <td>
                                    <form action="{{route('sessionpros.noterParticipant', [$sessionpro, $user])}}" method="post">
                                        @csrf
                                        @method('PUT')
                                        <input required type="number" name="note" value="{{ $sessionpro->users->firstWhere('id', $user->id)->pivot->note }}" min="0" max="20" step="0.01"> / 20
                                        <button type="submit" class="btn btn-secondary">Modifier note</button>
                                    </form>
                                </td>
                                <td>                                    
                                    <form action="{{route('inscription', $sessionpro)}}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('PUT')
                                        <button type="submit" class="btn btn-sm btn-danger mb-2" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce participant ?')">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        @else
                            <tr>
                                <td colspan="5">Aucun utilisateur inscrit</td>
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