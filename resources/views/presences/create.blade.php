@extends('layouts.myapp')
@section('content')
<div class="col-md-6 col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Presence</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form class="form form-horizontal" method="POST" action="{{route('presences.store')}}">
                            @csrf
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="date_debut">Date</label>
                                        </div>
                                        <div class="col-md-10 form-group">
                                            <input type="date" id="date" class="form-control" name="date">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="heure">Heure</label>
                                        </div>
                                        <div class="col-md-10 form-group">
                                            <input type="time" id="heure" class="form-control" name="heure">
                                        </div>

                                        <input type="hidden" id="sessionpro_id" class="form-control" name="sessionpro_id" value="{{$sessionpro->id}}">
                                        
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