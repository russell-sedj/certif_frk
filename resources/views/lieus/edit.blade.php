@extends('layouts.myapp')
@section('content')
<div class="col-md-6 col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Lieux</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form class="form form-horizontal" action="{{route('lieus.update', $lieu)}}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="nom">Ville</label>
                                        </div>
                                        <div class="col-md-10 form-group">
                                            <input type="text" id="ville" class="form-control" name="ville"
                                                placeholder="ville" value="{{$lieu->ville}}">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="description">Adresse</label>
                                        </div>
                                        <div class="col-md-10 form-group">
                                            <input type="text" id="description" class="form-control" name="adresse"
                                                placeholder="Adresse" value="{{$lieu->adresse}}">
                                        </div>
                                        <div class="col-sm-12 d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary me-1 mb-1">Modifier</button>
                                            <button type="reset"
                                                class="btn btn-light-secondary me-1 mb-1">Reset</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
@endsection