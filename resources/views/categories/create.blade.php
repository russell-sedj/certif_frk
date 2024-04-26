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
                                                    placeholder="Nom">
                                            </div>
                                            <div class="col-md-4">
                                                <label for="description">Description</label>
                                            </div>
                                            <div class="col-md-10 form-group">
                                                <input type="text" id="description" class="form-control" name="description"
                                                    placeholder="Description">
                                            </div>
                                            <div class="col-sm-12 d-flex justify-content-end">
                                                <button type="submit" class="btn btn-primary me-1 mb-1">Submit</button>
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