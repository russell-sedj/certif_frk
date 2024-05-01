@extends('layouts.myapp')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
            <div class="card-header">
            <h5 class="card-title">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Liste des catégories</span>
                        <a href="{{route('categories.create')}}" class="btn btn-success">Ajouter une catégorie</a>
                    </div>
            </h5>
                </div>
                @if (session('LockdeleteError'))
                <div class="alert alert-danger">
                        {{ session('LockdeleteError') }}
                    </div>             
               @endif
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col" >Nom</th>
                                <th scope="col" >Description</th>
                                <th scope="col" >Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($categories)
                          @foreach($categories as $category)
                            <tr>
                                <td>{{$category->nom}}</td>
                                <td>{{$category->description}}</td>
                                <td>
                                    <a href="{{route('categories.edit', $category)}}" class="btn btn-sm btn-warning"data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Modifier"><i class="bi bi-pencil-fill"></i></a>
                                    <form action="{{ url('/categories/'.$category->id)}}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"onclick="return confirm('Etes-vous sûr de vouloir supprimer cette catégorie?')"
                                data-bs-toggle="tooltip" data-bs-placement="top" title="Supprimer">
                                <i class="bi bi-trash3-fill"></i>
                                </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        @else
                        <tr>
                                <td colspan="5">Aucune catégorie trouvée.</td>
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
</div>
@endsection


