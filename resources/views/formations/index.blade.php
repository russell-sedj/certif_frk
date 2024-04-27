@extends('layouts.myapp')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
            <div class="card-header">
                <h1 class="mb-4 fs-2">Catégorie: {{ $categorie->nom }}</h1>
                <div class="d-flex justify-content-between align-items-center">
                    <h4>Liste des formations</h4>
                    <a href="{{route('formations.create',$categorie)}}" class="btn btn-success">Ajouter une formation</a>
                </div>
                </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Thème</th>
                                <th scope="col">Description</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                          @foreach($formations as $formation)
                            <tr>
                                <td>{{$formation->theme}}</td>
                                <td>{{$formation->description}}</td>
                                <td>
                                    <a href="{{route('sessionpros.index', $formation)}}" class="btn btn-sm btn-primary">Sessions</a>
                                    <a href="{{route('formations.edit', $formation)}}" class="btn btn-sm btn-secondary">Modifier</a>
                                    <form action="{{route('formations.destroy', [$formation->id, $formation->categorie])}}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('La suppression de cette formation entraînera également celle de toutes ses sessions et de toutes autres données qui y sont liées. Êtes-vous sûr de vouloir continuer?')">Supprimer</button>
                                    </form>
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
@endsection


