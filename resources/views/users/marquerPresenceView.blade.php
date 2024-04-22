@extends('layouts.myapp')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h1>Thème: {{ $sessionpro->formation->theme }}</h1>
                    <h4 class="mb-4">Lieu: {{$sessionpro->lieu->ville}}@if($sessionpro->lieu->adresse), {{$sessionpro->lieu->adresse}}@endif</h4>
                    <div class="d-flex justify-content-between align-items-center">
                        <h3>Marquer présence</h3>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-responsive">
                        <thead>
                            <tr>
                                <th scope="col">Date</th>
                                <th scope="col">Présence</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if($presences)
                        @foreach($presences as $presence)
                            <tr>
                                <td>{{$presence->date}}</td>
                                <td class="d-flex justify-content-start">
                                    <form action="{{route('users.marquerPresence', ['user' => $user, 'presence' => $presence] )}}" method="post">
                                        @csrf
                                        <input type="number" name="num_presence" min="1" max="{{$presence->nb_participants}}" {{$presence->est_actif ? '' : 'disabled'}} {{($presence->users()->where('user_id', $user->id)->exists()) ? 'hidden' : ''}} required>
                                        <button type="submit" {{(!$presence->est_actif || $presence->users()->where('user_id', $user->id)->exists()) ? 'hidden' : ''}} class="btn btn-primary">Valider</button>
                                    </form>
                                    
                                    @if ($presence->users()->where('user_id', $user->id)->exists())
                                        Numéro: {{$user->presences->firstWhere('id', $presence->id)->pivot->num_presence}}
                                        <i class="bi bi-check-circle-fill text-success ms-3"></i>
                                    @else
                                        <i class="bi bi-x-circle-fill text-danger ms-3"></i>
                                    @endif

                                    @if (session('error'))
                                    <span class="ms-3 alert alert-danger">{{ session('error') }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        @else
                            <tr>
                                <td colspan="5">Aucune présence faite</td>
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