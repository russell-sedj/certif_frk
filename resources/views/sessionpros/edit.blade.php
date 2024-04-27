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
                            <form class="form form-horizontal" action="{{route('sessionpros.update', $sessionpro)}}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="date_debut">Date de debut</label>
                                        </div>
                                        <div class="col-md-10 form-group">
                                            <input type="date" id="date_debut" class="form-control" name="date_debut"
                                            value="{{old('date_debut', $sessionpro->date_debut)}}">
                                            <x-input-error :messages="$errors->get('date_debut')" class="mt-2" />
                                        </div>
                                        <div class="col-md-4">
                                            <label for="date_fin">Date de fin</label>
                                        </div>
                                        <div class="col-md-10 form-group">
                                            <input type="date" id="date_fin" class="form-control" name="date_fin"
                                            value="{{old('date_fin', $sessionpro->date_fin)}}">
                                            <x-input-error :messages="$errors->get('date_fin')" class="mt-2" />
                                        </div>
                                        <div class="col-md-4">
                                            <label for="heure_debut">Heure de debut</label>
                                        </div>
                                        <div class="col-md-10 form-group">
                                            <input type="time" id="heure_debut" class="form-control" name="heure_debut"
                                            value="{{old('heure_debut', $sessionpro->heure_debut)}}">
                                            <x-input-error :messages="$errors->get('heure_debut')" class="mt-2" />
                                        </div>
                                        <div class="col-md-4">
                                            <label for="heure_fin">Heure de fin</label>
                                        </div>
                                        <div class="col-md-10 form-group">
                                            <input type="time" id="heure_fin" class="form-control" name="heure_fin"
                                            value="{{old('heure_fin', $sessionpro->heure_fin)}}">
                                            <x-input-error :messages="$errors->get('heure_fin')" class="mt-2" />
                                        </div>
                                        <div class="col-md-4">
                                            <label for="lieu_id">Lieu</label>
                                        </div>
                                        <div class="col-md-10 form-group">
                                            <select name="lieu_id" id="lieu_id">
                                            @foreach ($lieus as $lieu)
                                                <option value="{{old('lieu_id', $lieu->id)}}" {{ $sessionpro->lieu_id == $lieu->id ? 'selected' : '' }}>
                                                   {{$lieu->ville}}@if($lieu->adresse), {{$lieu->adresse}}@endif
                                                </option>
                                            @endforeach
                                            </select>
                                            <x-input-error :messages="$errors->get('lieu_id')" class="mt-2" />
                                        </div>
                                        <input type="hidden" id="formation_id" class="form-control" name="formation_id" value="{{$sessionpro->formation_id}}">
                                        <div class="col-sm-12 d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary me-1 mb-1">Modifier</button>
                                            @php
                                                $formation = \App\Models\Formation::find($sessionpro->formation_id);
                                            @endphp
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