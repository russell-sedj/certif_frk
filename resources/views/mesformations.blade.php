@extends('layouts.myapp')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
            <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <span> <h4>Formations suivies</h4></span>
                    </div>
                </div>
                    <table class="table table-responsive">
                        <thead>
                            <tr>
                                <th scope="col">Thème</th>
                                <th scope="col">Période</th>
                                <th scope="col">Lieu</th>
                                <th scope="col">Présence</th>
                                <th scope="col">Note</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                          @foreach($user->sessionpros as $sessionpro)
                            <tr>
                                <td>{{$sessionpro->formation->theme}}</td>
                                <td>{{$sessionpro->date_debut}}-{{$sessionpro->date_fin}}</td>
                                <td>{{$sessionpro->lieu->ville}}@if($sessionpro->lieu->adresse), {{$sessionpro->lieu->adresse}}@endif</td>
                                <td></td>
                                <td></td>
                                <td>
                                    <a href=""class="btn btn-sm btn-primary">Marquer une présence</a>
                                    @if($sessionpro->peut_demander)
                                    <a href="" class="btn btn-sm btn-secondary">Demander l'attestation</a>
                                    @endif
                                    <form action="" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


