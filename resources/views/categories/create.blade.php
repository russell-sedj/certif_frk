@extends('layouts.myapp')
@section('content')
<div class="row justify-content-center">
<div class="col-md-10 col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Catégorie</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form class="form form-horizontal" method="POST" action="{{route('categories.store')}}">
                            @csrf
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="nom">Nom</label>
                                        </div>
                                        <div class="col-md-10 form-group">
                                            <input type="text" id="nom" class="form-control" name="nom"
                                                placeholder="Nom" value="{{old('nom')}}" required>
                                                <x-input-error :messages="$errors->get('nom')" class="mt-2" />
                                        </div>
                                        <div class="col-md-4">
                                            <label for="description">Description</label>
                                        </div>
                                        <div class="col-md-10 form-group">
                                            <textarea id="description" class="form-control" name="description"
                                             placeholder="Description" value="{{old('description')}}" rows="4"></textarea>
                                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                                        </div>
                                        <div class="col-sm-12 d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary me-1 mb-1">Ajouter        </button>
                                            <a href="{{route('categories.index')}}" class="btn btn-light-secondary me-1 mb-1">Annuler</a>
                                        </div>

                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
</div>
@endsection