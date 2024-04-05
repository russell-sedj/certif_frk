@extends('layouts.myapp')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
            <div class="card-header">
                <h1 class="mb-4 fs-2">Thème: {{ $theme }}</h1>
                <h3 class="mb-4">Lieu: {{$sessionpro->lieu->ville}}@if($sessionpro->lieu->adresse), {{$sessionpro->lieu->adresse}}@endif</h3>
                <div class="d-flex justify-content-between align-items-center">
                    <h3>Liste des participants à la session</h3>
                    <a href="{{route('sessionpros.store',$formation)}}" class="btn btn-success">Ajouter une session</a>
                </div>
            </div>
            <div class="card-body">
                </div>
                    <table class="table table-responsive">
                        <thead>
                            <tr>
                                <th scope="col">Nom et Prénoms</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if($users)
                        @foreach($users as $user)
                            <tr>
                                <td>{{$user->name}} {{$user->surname}}</td>
                                <td>
                                    
                                    <a href="#" class="btn btn-sm btn-primary mb-2 mt-2">Noter</a>
                                    
                                    {{-- <form action="{{route('sessionpros.destroy', [$sessionpro->id, $sessionpro->formation])}}" method="POST" style="display: inline;"> --}}
                                    <form action="#" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger mb-2" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">Supprimer</button>
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
@endsection