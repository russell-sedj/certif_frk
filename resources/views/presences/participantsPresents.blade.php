@extends('layouts.myapp')
@section('content')
<link rel="stylesheet" href="/assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="/./assets/compiled/css/table-datatable-jquery.css">
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('sessionpros.dashboard')}}">Tableau de bord</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('presences.index', $sessionpro) }}">Présences</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Liste</li>
                    </ol>
                </nav>
                   <h3 class="card-title">
                    <div class="d-flex justify-content-between align-items-center">
                     Liste de présence
                    </div>
                    </h3>
                    <h4>
                    Thème: {{ $sessionpro->formation->theme }} <br>
                    Lieu: @if($sessionpro->lieu->adresse){{$sessionpro->lieu->adresse}}, @endif {{$sessionpro->lieu->ville}}<br>
                    Date: {{\Carbon\Carbon::parse($presence->date)->locale('fr')->isoFormat('D MMMM YYYY')}}
                    </h4>     
                    </div>
                    <div class="table-responsive">
                    <table class="table table-striped" id="table1">
                        <thead>
                            <tr>
                                <th scope="col"style="text-align:center;">Numéro</th>
                                <th scope="col"style="text-align:center;">Nom et Prénoms</th>
                                <th scope="col"style="text-align:center;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if($participants)
                        @foreach($participants as $participant)
                            <tr>
                                <td style="text-align:center">{{$participant->presences->firstWhere('id', $presence->id)->pivot->num_presence}}</td>
                                <td style="text-align:center">{{$participant['name']}} {{$participant['surname']}}</td>
                                <td style="text-align:center">
                                 <form action="{{route('presences.deleteParticipantPresent', [$presence, $participant])}}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir retirer ce participant de la liste ?')"data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Retirer"><i class="bi bi-person-dash-fill"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        @else
                            <tr>
                                <td colspan="5">Aucun participant présent</td>
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