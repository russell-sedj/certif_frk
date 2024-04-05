@extends('layouts.myapp')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
            <div class="card-header">
                <h1 class="mb-4 fs-2">Theme: {{ $theme }}</h1>
                <div class="d-flex justify-content-between align-items-center">
                    <h3>Session de {{ $sessionpro->lieu->ville }}</h3>
                </div>
            </div>
            <div class="card-body">
                </div>
                
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


