@extends('layouts.myapp')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
            <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Liste des catégories</span>
                        <a href="{{route('categories.create')}}" class="btn btn-success">Ajouter une catégorie</a>
                    </div>
                </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Nom</th>
                                <th scope="col">Description</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                          @foreach($categories as $category)
                            <tr>
                                <td>{{$category->nom}}</td>
                                <td>{{$category->description}}</td>
                                <td>
                                    <a href=""class="btn btn-sm btn-primary">Voir</a>
                                    <a href="{{route('categories.edit', $category)}}" class="btn btn-sm btn-secondary">Modifier</a>
                                    <form action="{{ url('/categories/'.$category->id)}}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
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


