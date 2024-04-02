@extends('layouts.myapp')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
            <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Mes formations</span>
                        
                    </div>
                </div>
                    <table class="table table-responsive">
                        <thead>
                            <tr>
                                <th scope="col">Thème</th>
                                <th scope="col">Date</th>
                                <th scope="col">Lieu</th>
                                <th scope="col">Nb presence</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if($sessionpros)
                        @foreach($sessionpros as $sessionpro)
                            <tr>
                                <td>{{$sessionpro->formation->theme}}</td>
                                <td>{{$sessionpro->date_debut}} - {{$sessionpro->date_fin}}</td>
                                <td>
                                        {{$sessionpro->lieu->ville}}@if($sessionpro->lieu->adresse), {{$sessionpro->lieu->adresse}}@endif                                    
                                </td>
                                <td>XX</td>
                                <td>
                                    <a href=""class="btn btn-sm btn-primary">Marquer présence</a>
                                    <a href="" class="btn btn-sm btn-secondary">Demander attestation</a>
                                </td>
                            </tr>
                        @endforeach
                        @else
                            <tr>
                                <td colspan="5">Aucune session trouvée</td>
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


