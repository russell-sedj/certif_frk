@extends('layouts.myapp')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
            <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Liste des lieux</span>
                        <a href="{{route('lieus.create')}}" class="btn btn-success">Ajouter un lieu</a>
                    </div>
                </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Villes</th>
                                <th scope="col">Adresses</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                          @foreach($lieux as $lieu)
                            <tr>
                                <td>{{$lieu->ville}}</td>
                                <td>{{$lieu->adresse}}</td>
                                <td>
                                    <a href="{{route('lieus.edit', $lieu)}}" class="btn btn-sm btn-secondary">Modifier</a>
                                    <form action="{{ url('/lieus/'.$lieu->id)}}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce lieu ?')">Supprimer</button>
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


