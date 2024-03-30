@extends('layouts.myapp')
@section('content')
<div class="col-md-6 col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Formation</h4>
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
                                            <input type="text" id="theme" class="form-control" name="theme"
                                                placeholder="Thème">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="description">Description</label>
                                        </div>
                                        <div class="col-md-10 form-group">
                                            <input type="text" id="description" class="form-control" name="description"
                                                placeholder="Description">
                                        </div>
                                        <div class="col-md-4">
                                             <label for="nombre_sessions">Nombre de sessions</label>
                                        </div>
                                        <div class="col-md-10 form-group">
                                          <input type="number" id="nombre_sessions" class="form-control" name="nombre_sessions" value="1" min="1">
                                        </div>
                                        <div id="sessions-container">
                                        <!-- Les champs de sessions générés dynamiquement seront placés ici -->
                                        </div>
                                        <input type="hidden" id="categorie_id" class="form-control" name="categorie_id" value="{{$categorie->id}}">
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
                                <input type="date" id="date_debut_${i}" class="form-control" name="date_debut_${i}">
                            </div>

                            <div class="col-md-4">
                                <label for="date_fin_${i}">Date de fin</label>
                            </div>
                            <div class="col-md-10 form-group">
                                <input type="date" id="date_fin_${i}" class="form-control" name="date_fin_${i}">
                            </div>

                            <div class="col-md-4">
                                <label for="heure_debut_${i}">Heure de début</label>
                            </div>
                            <div class="col-md-10 form-group">
                                <input type="time" id="heure_debut_${i}" class="form-control" name="heure_debut_${i}">
                            </div>

                            <div class="col-md-4">
                                <label for="heure_fin_${i}">Heure de fin</label>
                            </div>
                            <div class="col-md-10 form-group">
                                <input type="time" id="heure_fin_${i}" class="form-control" name="heure_fin_${i}">
                            </div>

                            <div class="col-md-4">
                                <label for="lieu_${i}">Lieu</label>
                            </div>
                            <div class="col-md-10 form-group">
                                <select id="lieu_${i}" class="form-control" name="lieu_${i}">
                                    <option value="">Choisissez un lieu</option>
                                    @foreach($lieus as $lieu)
                                         <option value="{{$lieu->id}}">{{$lieu->ville}}@if($lieu->adresse), {{$lieu->adresse}} @endif</option>
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