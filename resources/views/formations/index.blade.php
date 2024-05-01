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
                        <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Catégories</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Formations</li>
                    </ol>
                </nav>      
               <h5 class="card-title">
                <div class="d-flex justify-content-between align-items-center">
                    Liste des formations
                    <a href="{{route('formations.create',$categorie)}}" class="btn btn-success">Ajouter une formation</a>
                </div>
                </h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped" id="table1">
                        <thead>
                            <tr>
                                <th scope="col" style="text-align:center;">Thème</th>
                                <th scope="col"style="text-align:center;">Description</th>
                                <th scope="col"style="text-align:center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                          @foreach($formations as $formation)
                            <tr>
                                <td>{{$formation->theme}}</td>
                                <td>{{$formation->description}}</td>
                                <td style="display:flex; justify-content:center">
                                <div class="btn-group btn-group-space" role="group" aria-label="Actions">
                                    <a href="{{route('sessionpros.index', $formation)}}" class="btn btn-sm btn-primary mt-3">Sessions</a>
                                    <div>
                                    <a href="{{route('formations.edit', $formation)}}" class="btn btn-sm btn-secondary mt-3">Modifier</a></div>
                                    <form action="{{route('formations.destroy', [$formation->id, $formation->categorie])}}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger mt-3" onclick="return confirm('La suppression de cette formation entraînera également celle de toutes ses sessions et de toutes autres données qui y sont liées. Êtes-vous sûr de vouloir continuer?')">Supprimer</button>
                                    </form>
                                </div>
                                </td>
                            </tr>
                        @endforeach
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
<style>
    .btn-group-space .btn {
    margin: 0 5px; 
    height:35px;
}
</style>