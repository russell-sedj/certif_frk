@extends('layouts.myapp')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10 col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Profile Information</h4>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <form class="form form-horizontal" action="{{ route('profile.update', $user) }}" method="POST">
                        @csrf
                        @method('patch')
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="matricule">Matricule</label>
                                </div>
                                <div class="col-md-10 form-group">
                                    <input type="text" id="matricule" class="form-control" name="matricule" placeholder="Matricule" value="{{ old('matricule', $user->matricule) }}" required autofocus autocomplete="matricule">
                                    <x-input-error :messages="$errors->get('matricule')" class="mt-2" />
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <label for="name">Nom</label>
                                </div>
                                <div class="col-md-10 form-group">
                                    <input type="text" id="name" class="form-control" name="name" placeholder="Name" value="{{ old('name', $user->name) }}" required autocomplete="name">
                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <label for="surname">Prénoms</label>
                                </div>
                                <div class="col-md-10 form-group">
                                    <input type="text" id="surname" class="form-control" name="surname" placeholder="Prénoms" value="{{ old('surname', $user->surname) }}" required autocomplete="surname">
                                    <x-input-error :messages="$errors->get('surname')" class="mt-2" />
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <label for="sexe">Sexe</label>
                                </div>
                                <div class="col-md-10 form-group">
                                    <select id="sexe" name="sexe" class="form-select">
                                        <option value="M" @if(old('sexe', $user->sexe) == 'M') selected @endif>Masculin</option>
                                        <option value="F" @if(old('sexe', $user->sexe) == 'F') selected @endif>Féminin</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('sexe')" class="mt-2" />
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <label for="email">Email</label>
                                </div>
                                <div class="col-md-10 form-group">
                                    <input type="email" id="email" class="form-control" name="email" placeholder="Email" value="{{ old('email', $user->email) }}" required autocomplete="email">
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <label for="titre">Titre</label>
                                </div>
                                <div class="col-md-10">
                                    <div class="form-check">
                                        <input type="radio" id="titre1" class="form-check-input" name="titre" value="DGTCP" @if(old('titre', $user->titre) == 'DGTCP') checked @endif>
                                        <label class="form-check-label" for="titre1">DGTCP</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="radio" id="titre2" class="form-check-input" name="titre" value="DFPT" @if(old('titre', $user->titre) == 'DFPT') checked @endif>
                                        <label class="form-check-label" for="titre2">DFPT</label>
                                    </div>
                                    <div class="form-check">
                                    <input type="radio" id="titre3" class="form-check-input" name="titre" value="" @if(old('titre', $user->titre) == null) checked @endif>
                                        <label class="form-check-label" for="titre3">Autre</label>
                                    </div>
                                    <x-input-error :messages="$errors->get('titre')" class="mt-2" />
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <label for="role">Rôle</label>
                                </div>
                                <div class="col-md-10">
                                <div class="form-check">
                                        <input type="radio" id="role1" class="form-check-input" name="role" value="admin" @if(old('role', $user->role) == 'admin') checked @endif>
                                        <label class="form-check-label" for="role1">Admin</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="radio" id="role2" class="form-check-input" name="role" value="superviseur" @if(old('role', $user->role) == 'superviseur') checked @endif>
                                        <label class="form-check-label" for="role2">Superviseur</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="radio" id="role3" class="form-check-input" name="role" value="aucun" @if(old('role', $user->role) == 'aucun') checked @endif>
                                        <label class="form-check-label" for="role3">Aucun</label>
                                    </div>
                                    <x-input-error :messages="$errors->get('role')" class="mt-2" />
                            </div>

                            <div class="row">
                                <div class="col-sm-12 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary me-1 mb-1">Modifier</button>
                                    <a href="{{route('agents')}}" class="btn btn-light-secondary me-1 mb-1">Annuler</a>
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
