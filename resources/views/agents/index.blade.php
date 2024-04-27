@extends('layouts.myapp')
@section('content')
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                <div class="d-flex justify-content-between align-items-center">
                        <span>Liste des agents</span>
                        <a href="{{route('register')}}" class="btn btn-success">Ajouter un agent</a>
                    </div>
                </h5>
            </div>
            <div class="card-body">
                <table class="table table-responsive table-striped" id="table1">
                    <thead>
                        <tr>
                            <th>Matricule</th>
                            <th>Nom</th>
                            <th>Prénoms</th>
                            <th>Sexe</th>
                            <th>Email</th>
                            <th>Titre</th>
                            <th>Rôle</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>{{$user->matricule}}</td>
                            <td>{{$user->name}}</td>
                            <td>{{$user->surname}}</td>
                            <td>{{$user->sexe}}</td>
                            <td>{{$user->email}}</td>
                            <td>{{$user->titre}}</td>
                            <td>{{$user->role}}</td>
                            <td>
                                <a href="{{route('profile.edit', $user)}}" class="btn btn-sm btn-secondary">Modifier</a>
                            </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

@endsection