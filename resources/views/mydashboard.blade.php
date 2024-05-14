@extends('layouts.myapp')
@section('content')
<div class="container">
    <div class="row">
        @if($categories->count()==0)
        <div class="card">
        <div class="card-body">
              Aucune formation disponible.
        </div>
     </div>
      @else
    @foreach($categories as $category)
        <div class="col-lg-5 col-md-5 mb-4 mr-9">
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
    @endif
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