@extends('layouts.myapp')
@section('content')
    <section id="multiple-column-form">
    <div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Agent</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('register') }}" class="row g-3" id="myForm">
                    @csrf
                    <div class="col-md-6">
                        <div class="form-group">
                            <x-input-label for="matricule" :value="__('Matricule')" />
                            <x-text-input id="matricule" class="form-control" type="text" name="matricule" :value="old('matricule')"  required autofocus autocomplete="matricule" />
                            <x-input-error :messages="$errors->get('matricule')" class="mt-2" />
                        </div>
                        <div class="form-group">
                            <x-input-label for="name" :value="__('Nom')" />
                            <x-text-input id="name" class="form-control" type="text" name="name" :value="old('name')" required autocomplete="name" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                        <div class="form-group">
                            <x-input-label for="surname" :value="__('Prénoms')" />
                            <x-text-input id="surname" class="form-control" type="text" name="surname" :value="old('surname')" required autocomplete="surname" />
                            <x-input-error :messages="$errors->get('surname')" class="mt-2" />
                        </div>
                        <div class="form-group">
                            <x-input-label for="sexe" :value="__('Sexe')" />
                            <select class="form-select" id="sexe" name="sexe">
                                <option disabled selected>Choisissez...</option>
                                <option value="M" @if(old('sexe')=="M") selected @endif>Masculin</option>
                                <option value="F" @if(old('sexe')=="F") selected @endif>Féminin</option>
                            </select>
                            <x-input-error :messages="$errors->get('sexe')" class="mt-2" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="form-control" type="email" name="email" :value="old('email')" required autocomplete="username" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                        <div class="form-group">
                            <x-input-label for="password" :value="__('Mot de passe')" />
                            <x-text-input id="password" class="form-control" type="password" name="password" required autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>
                        <div class="form-group">
                            <x-input-label for="password_confirmation" :value="__('Confirmer le mot de passe')" />
                            <x-text-input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <x-input-label for="titre" :value="__('Titre')" />
                            <div class="form-check">
                                <input type="radio" id="titre1" class="form-check-input" name="titre" value="DGTCP">
                                <label class="form-check-label" for="titre1">DGTCP</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" id="titre2" class="form-check-input" name="titre" value="DFPT">
                                <label class="form-check-label" for="titre2">DFPT</label>
                            </div>
                            <x-input-error :messages="$errors->get('titre')" class="mt-2" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" id="role" class="form-check-input" name="role" value="old('role')">
                                <label for="role">Agent Superviseur</label>
                            </div>
                            <x-input-error :messages="$errors->get('role')" class="mt-2" />
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


        </section>
        <script>
     document.addEventListener('DOMContentLoaded', function() {
        var roleCheckbox = document.getElementById('role');
        var valueInput = document.querySelector('input[name="role"]');
        var form = document.getElementById('myForm');
        
        form.addEventListener('submit', function(event) {
            if (roleCheckbox.checked) {
                valueInput.value = "superviseur";
            } else {
                valueInput.value = "";
            }
        });
    });
</script>

@endsection
