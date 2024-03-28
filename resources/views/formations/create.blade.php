@extends('layouts.myapp')
@section('content')
<div class="col-md-6 col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Formation</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form class="form form-horizontal" method="POST" action="{{route('formations.store')}}">
                            @csrf
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="theme">Thème</label>
                                        </div>
                                        <div class="col-md-10 form-group">
                                            <input type="text" id="theme" class="form-control" name="theme"
                                                placeholder="Thème">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="description">Description</label>
                                        </div>
                                        <div class="col-md-10 form-group">
                                            <input type="text" id="description" class="form-control" name="description"
                                                placeholder="Description">
                                        </div>
                                        <input type="hidden" id="categorie_id" class="form-control" name="categorie_id" value="{{$categorie->id}}">
                                        <div class="col-sm-12 d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary me-1 mb-1">Submit</button>
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