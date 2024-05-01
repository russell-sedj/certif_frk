@extends('layouts.myapp')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
            <div class="card-header">
            <h5 class="card-title">
                <div class="d-flex justify-content-between align-items-center">
                        <span>Liste des lieux</span>
                        <a href="{{route('lieus.create')}}" class="btn btn-success">Ajouter un lieu</a>
                    </div>
                </h5>
                </div>
                @if (session('lockdeleteError'))
                <div class="alert alert-danger">
                        {{ session('lockdeleteError') }}
                    </div>             
               @endif
                <div class="table-responsive">
                    <table class="table">        
                         <thead>
                            <tr>
                                <th scope="col">Ville</th>
                                <th scope="col">Adresse</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                          @foreach($lieux as $lieu)
                            <tr>
                                <td>{{$lieu->ville}}</td>
                                <td>{{$lieu->adresse}}</td>
                                <td>
                                    <a href="{{route('lieus.edit', $lieu)}}" class="btn btn-sm btn-warning"><i class="bi bi-pencil-fill"></i></a>
                                    <form action="{{ url('/lieus/'.$lieu->id)}}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Etes-vous sûr de vouloir supprimer ce lieu ?')"><i class="bi bi-trash3-fill"></i></button>
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
</div>
@endsection


