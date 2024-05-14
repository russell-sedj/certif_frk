@extends('layouts.myapp')
@section('content')
<link rel="stylesheet" href="/assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="/./assets/compiled/css/table-datatable-jquery.css">

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h1>Tableau de bord</h1>
                </div>
            </div>
            <div class="card-body">
                @if (session('PeutInscrireError'))
                    <div class="alert alert-danger">
                        {{ session('PeutInscrireError') }}
                    </div>
                @endif
                @if (session('openRequestError'))
                    <div class="alert alert-danger">
                        {{ session('openRequestError') }}
                    </div>
                @endif
            </div>
            <div class="table-responsive"> 
                <table class="table table-striped" id="table1">
                    <thead>
                        <tr>
                            <th scope="col" style="text-align:center;">Thème</th>
                            <th scope="col" style="text-align:center;">Période</th>
                            <th scope="col" style="text-align:center;">Présences effectuées</th>
                            <th scope="col" style="text-align:center;">Lieu</th>
                            <th scope="col" style="text-align:center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if($sessionpros)
                    @foreach($sessionpros as $sessionpro)
                        <tr>
                            <td>{{$sessionpro->formation->theme}}</td>

                            <td>@if($sessionpro->date_debut==$sessionpro->date_fin)
                            Journée du {{ \Carbon\Carbon::parse($sessionpro->date_debut)->locale('fr')->isoFormat('D MMMM YYYY')}} @else
                                {{ \Carbon\Carbon::parse($sessionpro->date_debut)->locale('fr')->isoFormat('D MMMM')}}
                                 au {{\Carbon\Carbon::parse($sessionpro->date_fin)->locale('fr')->isoFormat('D MMMM YYYY')}} @endif
                                </td>

                            <td class="text-center">{{ $sessionpro->presences->count() }}</td>

                            <td>
                                @if($sessionpro->lieu->adresse){{$sessionpro->lieu->adresse}}, @endif {{$sessionpro->lieu->ville}}                                   
                            </td>
                            
                            <td>
                            <div class="btn-group btn-group-space" role="group" aria-label="Actions">
                            <form action="{{route('sessionpros.actionInscription', $sessionpro)}}" method="post">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm mb-2 mt-3 {{$sessionpro->peut_inscrire ? 'btn-warning' : 'btn-success'}}" data-bs-toggle="tooltip" data-bs-placement="left"
                                title="{{$sessionpro->peut_inscrire ? 'Clôturer les inscriptions' : 'Ouvrir les inscriptions'}}">
                                        @if(!$sessionpro->peut_inscrire)<i class="bi bi-person-plus-fill"></i>@else<i class="bi bi-person-fill-lock"></i>@endif
                                    </button>
                                </form>    
                            <a href="{{ route('sessionpros.participants', $sessionpro) }}" class="btn btn-sm btn-info mb-2 mt-3"data-bs-toggle="tooltip" data-bs-placement="left"
                                title="Participants"><i class="bi bi-people-fill"></i></a>
                                
                                <a href="{{ route('presences.index', $sessionpro) }}" class="btn btn-sm btn-primary mb-2 mt-3" data-bs-toggle="tooltip" data-bs-placement="left"
                                title="Présences"><i class="bi bi-person-check-fill"></i></a>
                                @if(!$sessionpro->peut_demander)
                                    <form action="{{route('sessionpros.openRequest', $sessionpro)}}" method="post">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-sm btn-success mb-2 mt-3"  
                                        onclick="return confirm('Cette action marque la fin de la session et est irréversible. Voulez-vous continuer?')" data-bs-toggle="tooltip" data-bs-placement="left"
                                        title="Autoriser les demandes">
                                        <i class="bi bi-mortarboard-fill"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                            </td>
                        </tr>
                    @endforeach
                    @else
                        <tr>
                            <td colspan="5">Aucune session trouvée</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
          </div>
        </div>
    </div>
</div>
<script src="/assets/extensions/jquery/jquery.min.js"></script>
<script src="/assets/extensions/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="/assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
<script src="/assets/static/js/pages/datatables.js"></script>
@endsection
<style>
    .btn-group-space .btn {
    margin: 0 5px; 
    height:32px;
}
</style>

