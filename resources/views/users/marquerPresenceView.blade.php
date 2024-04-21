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
                                    <form method="post" action="{{route('users.marquerPresence', ['user' => $user, 'presence' => $presence] )}}">
                                        @csrf
                                        
                                        <input required type="number" name="num_presence" min="1" max="{{$presence->nb_participants}}"
                                            {{$presence->est_actif ? '' : 'disabled'}}
                                            value="{{$user->presences->firstWhere('id', $presence->id)->pivot->num_presence ?? ''}}"
                                        >
                                        
                                        @if ($user->presences->firstWhere('id', $presence->id))
                                            <input type="hidden" name="modifiy">
                                        @endif

                                        <button type="submit" class="btn ms-4 {{$user->presences->firstWhere('id', $presence->id) ? 'btn-warning' : 'btn-primary'}}"
                                            {{(!$presence->est_actif ) ? 'hidden' : ''}}
                                        >
                                            {{$user->presences->firstWhere('id', $presence->id) ? 'Modifier' : 'Valider'}}
                                        </button>
                                    </form>
                                    {{-- <form method="post" action="{{route('users.marquerPresence', ['user' => $user, 'presence' => $presence] )}}"
                                        {{$user->presences->firstWhere('id', $presence->id) ? 'hidden' : ''}}
                                    >
                                        @csrf
                                        <input required type="number" name="num_presence" min="1" max="{{$presence->nb_participants}}"
                                            {{$presence->est_actif ? '' : 'disabled'}}
                                            {{($presence->users()->where('user_id', $user->id)->exists()) ? 'hidden' : ''}}
                                        >
                                        <button type="submit" class="btn btn-primary"
                                            {{(!$presence->est_actif || $presence->users()->where('user_id', $user->id)->exists()) ? 'hidden' : ''}}
                                        >
                                            Valider
                                        </button>
                                    </form> --}}
                                    
                                    @if ($presence->users()->where('user_id', $user->id)->exists())
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