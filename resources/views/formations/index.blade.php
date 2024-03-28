@extends('layouts.myapp')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
            <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Liste des formations</span>
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
                                    <a href=""class="btn btn-sm btn-primary">Voir</a>
                                    <a href="{{route('formations.edit', $formation)}}" class="btn btn-sm btn-secondary">Modifier</a>
                                    <form action="{{route('formations.destroy', [$formation->id, $formation->categorie])}}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette formation ?')">Supprimer</button>
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


