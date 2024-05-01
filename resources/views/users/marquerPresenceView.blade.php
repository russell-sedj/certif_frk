@extends('layouts.myapp')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('mesformations')}}">Tableau de bord</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Présences</li>
                    </ol>
                </nav>
                <h3 class="card-title">
                    <div class="d-flex justify-content-between align-items-center">
                     Présences
                    </div>
                    </h3>
                    <h4>
                    Thème: {{ $sessionpro->formation->theme  }} <br>
                    Lieu: @if($sessionpro->lieu->adresse){{$sessionpro->lieu->adresse}}, @endif {{$sessionpro->lieu->ville}}<br>
                    </h4>        
                </div>
                <div class="card-body">         
                @if (session('error'))
                <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>             
               @endif
                 </div>
                 <div class="table-responsive">
                    <table class="table">
                     <thead>
                            <tr>
                                <th scope="col" style="text-align:center;">Date</th>
                                <th scope="col" style="text-align:center;">Créée à</th>                                
                                <th scope="col">Numéro</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if($presences)
                        @foreach($presences as $presence)
                            <tr>
                            <td style="text-align:center;">
                                {{\Carbon\Carbon::parse($presence->date)->locale('fr')->isoFormat('D MMMM YYYY')}}
                               </td>
                               <td style="text-align:center;">
                               {{date('H:i', strtotime($presence->created_at))}}
                               </td>
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
                                    
                                    @if ($presence->users()->where('user_id', $user->id)->exists())
                                        <i class="bi bi-check-circle-fill text-success ms-3"></i>
                                    @else
                                        <i class="bi bi-x-circle-fill text-danger ms-3"></i>
                                    @endif

                                    @if (!$presence->est_actif)
                                        <div class="badge text-bg-secondary ms-3 d-flex align-items-center justify-content-center">Fermé</div>
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
</div>
@endsection