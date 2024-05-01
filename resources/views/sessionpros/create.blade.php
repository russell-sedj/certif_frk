@extends('layouts.myapp')
@section('content')
<div class="row justify-content-center">
<div class="col-md-10 col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Session</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form class="form form-horizontal" method="POST" action="{{route('sessionpros.store')}}">
                            @csrf
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="date_debut">Date de debut</label>
                                        </div>
                                        <div class="col-md-10 form-group">
                                            <input type="date" id="date_debut" class="form-control" name="date_debut" value="{{old('date_debut')}}" required>
                                            <x-input-error :messages="$errors->get('date_debut')" class="mt-2" />
                                        </div>
                                        <div class="col-md-4">
                                            <label for="date_fin">Date de fin</label>
                                        </div>
                                        <div class="col-md-10 form-group">
                                            <input type="date" id="date_fin" class="form-control" name="date_fin" value="{{old('date_fin')}}" required>
                                            <x-input-error :messages="$errors->get('date_fin')" class="mt-2" />
                                        </div>
                                        <div class="col-md-4">
                                            <label for="heure_debut">Heure de debut</label>
                                        </div>
                                        <div class="col-md-10 form-group">
                                            <input type="time" id="heure_debut" class="form-control" name="heure_debut" value="{{old('heure_debut')}}" required>
                                            <x-input-error :messages="$errors->get('heure_debut')" class="mt-2" />
                                        </div>
                                        <div class="col-md-4">
                                            <label for="heure_fin">Heure de fin</label>
                                        </div>
                                        <div class="col-md-10 form-group">
                                            <input type="time" id="heure_fin" class="form-control" name="heure_fin" value="{{old('heure_fin')}}" required>
                                            <x-input-error :messages="$errors->get('heure_fin')" class="mt-2" />
                                        </div>
                                        <div class="col-md-4">
                                            <label for="lieu_id">Lieu</label>
                                        </div>
                                        <div class="col-md-10 form-froup">
                                            <select id="lieu_id" name="lieu_id" required>
                                            <option disabled selected>Choisissez...</option>
                                                @foreach($lieus as $lieu)
                                                    <option value="{{$lieu->id}}" @if(old('lieu_id')==$lieu->id) selected @endif>@if($lieu->adresse){{$lieu->adresse}}, @endif{{$lieu->ville}}</option>
                                                @endforeach
                                            </select>
                                            <x-input-error :messages="$errors->get('lieu_id')" class="mt-2" />
                                        </div>

                                        <input type="hidden" id="formation_id" class="form-control" name="formation_id" value="{{$formation}}">
                                        
                                        <div class="col-sm-12 d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary me-1 mb-1">Ajouter              </button>
                                            <a href="{{route('sessionpros.index', $formation)}}" class="btn btn-light-secondary me-1 mb-1">Annuler</a>
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