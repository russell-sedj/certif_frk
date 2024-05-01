@extends('layouts.myapp')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10 col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Informations</h4>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <form class="form form-horizontal">
                        @csrf
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="matricule">Matricule</label>
                                </div>
                                <div class="col-md-10 form-group">
                                    <input type="text" id="matricule" class="form-control" value="{{ $user->matricule }}" readonly>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <label for="name">Nom</label>
                                </div>
                                <div class="col-md-10 form-group">
                                    <input type="text" id="name" class="form-control" value="{{ $user->name }}" readonly>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <label for="surname">Prénoms</label>
                                </div>
                                <div class="col-md-10 form-group">
                                    <input type="text" id="surname" class="form-control" value="{{ $user->surname }}" readonly>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <label for="sexe">Sexe</label>
                                </div>
                                <div class="col-md-10 form-group">
                                    <input type="text" id="sexe" class="form-control" value="{{ $user->sexe==='M'?"Masculin":"Féminin" ;}}" readonly>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <label for="email">Email</label>
                                </div>
                                <div class="col-md-10 form-group">
                                    <input type="text" id="email" class="form-control" value="{{ $user->email }}" readonly>
                                </div>
                            </div>
                            @if($user->titre!='')
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="titre">Titre</label>
                                </div>
                                <div class="col-md-10 form-group">
                                    <input type="text" id="titre" class="form-control" value="{{ $user->titre }}" readonly>
                                </div>
                            </div>
                            @endif
                            @if($user->role!='aucun')
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="role">Rôle</label>
                                </div>
                                <div class="col-md-10 form-group">
                                    <input type="text" id="role" class="form-control" value="{{ $user->role }}" readonly>
                                </div>
                            </div>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>    
@endsection
