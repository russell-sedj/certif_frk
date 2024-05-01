@extends('layouts.myapp')
@section('content')
<link rel="stylesheet" href="/assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="/./assets/compiled/css/table-datatable-jquery.css">
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('sessionpros.dashboard')}}">Tableau de bord</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Participants</li>
                    </ol>
                </nav>
                    <h3 class="card-title">
                    <div class="d-flex justify-content-between align-items-center">
                     Participants
                    </div>
                    </h3>
                    <h4>
                    Thème: {{ $theme }} <br>
                    Lieu: @if($sessionpro->lieu->adresse){{$sessionpro->lieu->adresse}}, @endif {{$sessionpro->lieu->ville}}<br>
                    </h4>        
                </div>
                <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="table1">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center">Nom et Prénoms</th>
                                <th scope="col" class="text-center">Nombre de présences</th>
                                <th scope="col" class="text-center">Note /20</th>
                                <th scope="col" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if($users)
                        @foreach($users as $user)
                            <tr>
                                <td class="text-center">{{$user->name}} {{$user->surname}}</td>
                                <td class="text-center">{{$user->presences->where('sessionpro_id', $sessionpro->id)->count()}}/{{$sessionpro->presences->count()}}</td>
                                <td style="display:flex; justify-content:center">
                                    <form action="{{route('sessionpros.noterParticipant', [$sessionpro, $user])}}" method="post">
                                        @csrf
                                        @method('PUT')
                                        <input required type="number" name="note" value="{{ $sessionpro->users->firstWhere('id', $user->id)->pivot->note }}" min="0" max="20" step="0.05">
                                        <button type="submit" class="btn btn-warning"data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Modifier"><i class="bi bi-pencil-fill"></i></button>
                                    </form>
                                </td>
                                <td class="text-center">                                    
                                    <form action="{{route('inscription', $sessionpro)}}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('PUT')
                                        <button type="submit" class="btn btn-sm btn-danger mb-2" onclick="return confirm('Êtes-vous sûr de vouloir retirer ce participant ?')"data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Retirer"><i class="bi bi-person-dash-fill"></i></button>
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
</div>
<script src="/assets/extensions/jquery/jquery.min.js"></script>
<script src="/assets/extensions/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="/assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
<script src="/assets/static/js/pages/datatables.js"></script>
@endsection