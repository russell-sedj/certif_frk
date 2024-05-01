@extends('layouts.myapp')
@section('content')
<link rel="stylesheet" href="/assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="/./assets/compiled/css/table-datatable-jquery.css">
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
            <div class="table-responsive">
                <table class="table table-striped" id="table1">
                    <thead>
                        <tr>
                            <th style="text-align:center;">Matricule</th>
                            <th style="text-align:center;">Nom</th>
                            <th style="text-align:center;">Prénoms</th>
                            <th style="text-align:center;">Sexe</th>
                            <th style="text-align:center;">Email</th>
                            <th style="text-align:center;">Titre</th>
                            <th style="text-align:center;">Rôle</th>
                            <th style="text-align:center;">Action</th>
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
                                <a href="{{route('profile.edit', $user)}}" class="btn btn-sm btn-warning"
                                data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Modifier"><i class="bi bi-pencil-fill"></i></a>
                            </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
        <script src="/assets/extensions/jquery/jquery.min.js"></script>
<script src="/assets/extensions/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="/assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
<script src="/assets/static/js/pages/datatables.js"></script>
@endsection