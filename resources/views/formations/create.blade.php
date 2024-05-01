@extends('layouts.myapp')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-10 col-12">
<div class="card-body">
                @if (session('AddSessionError'))
                    <div class="alert alert-danger">
                        {{ session('AddSessionError') }}
                    </div>
                @endif
            </div>
                <div class="card">
                    <div class="card-header">
                    <h6 class="mb-4 fs-2">{{ $categorie->nom }}</h6>
                <h4 class="card-title">Ajouter une formation</h4>
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
                                            <textarea id="theme" class="form-control" name="theme"
                                                placeholder="Thème" value="{{old('theme')}}"rows="2" required></textarea>
                                                <x-input-error :messages="$errors->get('theme')" class="mt-2" />
                                        </div>
                                        <div class="col-md-4">
                                            <label for="description">Description</label>
                                        </div>
                                        <div class="col-md-10 form-group">
                                            <textarea id="description" class="form-control" name="description"
                                                placeholder="Description" value="{{old('description')}}" rows="4"></textarea>
                                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                                        </div>
                                        <div class="col-md-4">
                                             <label for="nombre_sessions">Nombre de sessions</label>
                                        </div>
                                        <div class="col-md-10 form-group">
                                          <input type="number" id="nombre_sessions" class="form-control" name="nombre_sessions" value="0" min="0">
                                        </div>
                                        <div id="sessions-container">
                                        <!-- Les champs de sessions générés dynamiquement seront placés ici -->
                                        </div>
                                        <input type="hidden" id="categorie_id" class="form-control" name="categorie_id" value="{{$categorie->id}}">
                                        <div class="col-sm-12 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary me-1 mb-1">Ajouter              </button>
                                    <a href="{{route('formations.index', $categorie)}}" class="btn btn-light-secondary me-1 mb-1">Annuler</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
            <script>
    document.addEventListener('DOMContentLoaded', function() {
        var nombreSessionsInput = document.getElementById('nombre_sessions');

        nombreSessionsInput.addEventListener('change', function() {
            var nombreSessions = parseInt(this.value);
            var sessionsContainer = document.getElementById('sessions-container');

            sessionsContainer.innerHTML = ''; // Réinitialiser le contenu

            for (var i = 0; i < nombreSessions; i++) {
                sessionsContainer.innerHTML += `
                    <div class="form-body">
                        <h4>Session ${i + 1}</h4> 
                        <div class="row">
                            <div class="col-md-4">
                                <label for="date_debut_${i}">Date de début</label>
                            </div>
                            <div class="col-md-10 form-group">
                                <input type="date" id="date_debut_${i}" class="form-control" name="date_debut_${i}" value="{{old('date_debut_${i}')}}">
                            </div>

                            <div class="col-md-4">
                                <label for="date_fin_${i}">Date de fin</label>
                            </div>
                            <div class="col-md-10 form-group">
                                <input type="date" id="date_fin_${i}" class="form-control" name="date_fin_${i}" value="{{old('date_fin_${i}')}}">
                            </div>

                            <div class="col-md-4">
                                <label for="heure_debut_${i}">Heure de début</label>
                            </div>
                            <div class="col-md-10 form-group">
                                <input type="time" id="heure_debut_${i}" class="form-control" name="heure_debut_${i}" value="{{old('heure_debut_${i}')}}">
                            </div>

                            <div class="col-md-4">
                                <label for="heure_fin_${i}">Heure de fin</label>
                            </div>
                            <div class="col-md-10 form-group">
                                <input type="time" id="heure_fin_${i}" class="form-control" name="heure_fin_${i}" value="{{old('heure_fin_${i}')}}">
                            </div>

                            <div class="col-md-4">
                                <label for="lieu_${i}">Lieu</label>
                            </div>
                            <div class="col-md-10 form-group">
                                <select id="lieu_${i}" class="form-control" name="lieu_${i}">
                                    <option value="" disabled selected>Choisissez un lieu</option>
                                    @foreach($lieus as $lieu)
                                         <option value="{{$lieu->id}}" @if(old('lieu_${i}')==$lieu->id) selected @endif>@if($lieu->adresse){{$lieu->adresse}}, @endif{{$lieu->ville}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                `;
            }
        });
    });
</script>


@endsection