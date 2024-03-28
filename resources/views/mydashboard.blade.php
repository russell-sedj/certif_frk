@extends('layouts.myapp')
@section('content')
<div class="container">
    <div class="row">
    @foreach($categories as $category)
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h4 class="card-title">{{$category->nom}}</h4>
                    <p class="card-text">{{$category->description}}</p>
                </div>
                <div class="card-footer">
                    <a href="{{route('formations.index', $category)}}" class="btn btn-primary">Formations</a>
                </div>
            </div>
        </div>
    @endforeach
    </div>
</div>
<style>
    .card {
        transition: transform 0.3s ease;
    }

    .card:hover {
        transform: scale(1.05);
    }
</style>
@endsection