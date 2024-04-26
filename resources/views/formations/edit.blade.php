@extends('layouts.myapp')
@section('content')

@php
    $categorie = \App\Models\Categorie::find($formation->categorie_id);
@endphp

<div class="col-md-10 col-12">
    <div class="card">
        <div class="card-header">
            <h1 class="mb-4 fs-2">Catégorie: {{ $categorie->nom }}</h1>
            <h4 class="card-title">Modifier une fomation</h4>
        </div>
        <div class="card-content">
            <div class="card-body">
                <form class="form form-horizontal" action="{{route('formations.update', $formation)}}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="theme">Thème</label>
                            </div>
                            <div class="col-md-10 form-group">
                                <input type="text" id="theme" class="form-control" name="theme"
                                    placeholder="Thème" value="{{$formation->theme}}">
                            </div>
                            <div class="col-md-4">
                                <label for="description">Description</label>
                            </div>
                            <div class="col-md-10 form-group">
                                <input type="text" id="description" class="form-control" name="description"
                                    placeholder="Description" value="{{$formation->description}}">
                            </div>
                            <input type="hidden" id="categorie_id" class="form-control" name="categorie_id" value="{{$formation->categorie_id}}">
                            <div class="col-sm-12 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary me-1 mb-1">Modifier</button>
                                <a href="{{route('formations.index', $categorie)}}" class="btn btn-light-secondary me-1 mb-1">Annuler</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection