@extends('layouts.myapp')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <h3 class="m-b-30 heading-line">Demandes d'attestation</h3>
            <div class="mt-3 d-flex justify-content-end">
                <button class="btn btn-primary" id="downloadBtn" data-bs-toggle="modal" data-bs-target="#downloadModal">Télécharger l'attestation</button>
            </div>
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#demandes">Demandes @if($cpt) ({{$cpt}} en attente) @endif</a>
                </li>
            </ul>
            <div class="tab-content">
                <div id="demandes" class="tab-pane fade show active">
                    <div class="notification-ui_dd-content">
                        @foreach($demandes as $demande)
                        <div class="notification-list mt-2 @if(!$demande->prete) notification-list--unread @endif ">
                            <div class="notification-list_content">
                                <div class="notification-list_detail d-flex align-items-center justify-content-between">
                                    <div>
                                        <p><b>{{ $demande->user->name}} {{ $demande->user->surname}}</b></p>
                                        <p class="text-muted">
                                        Nouvelle demande d'attestation pour le thème : <strong>{{$demande->libelle_demande}}</strong>.
                                        </p>
                                        <p class="text-muted"><small>{{ $demande->created_at }}</small></p>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <button class="btn btn-info btn-sm ms-2 mt-3 detailsBtn" 
                                    data-toggle="modal" data-target="#detailsModal"
                                    data-presence="@if(!$demande->sessionproUser->nb_presence){{0}}@else{{$demande->sessionproUser->nb_presence}}@endif/{{$demande->sessionpro->presences->count()}}"
                                    data-note="{{ $demande->sessionproUser->note}}"
                                    data-lieu="@if($demande->sessionpro->lieu->adresse) {{$demande->sessionpro->lieu->adresse}}, @endif {{$demande->sessionpro->lieu->ville}} "
                                    data-periode="{{$demande->sessionpro->date_debut}} - {{$demande->sessionpro->date_fin}}">Détails</button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailsModalLabel">Détails de la participation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Détails de la participation</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="downloadModal" tabindex="-1" aria-labelledby="reponseModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reponseModalLabel">Période</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="downloadForm" method="POST" action="{{route('demandes.download')}}">
                    @csrf
                    <div class="mb-3">
                        <label for="debut" class="form-label">Date de début :</label>
                        <select class="form-select" id="debut" name="debut">
                            <option value="">Choisir une date</option>
                            @foreach($demandes as $demande)
                            @if(!$demande->notif_reponse)
                                <option value="{{ $demande->created_at }}">{{ $demande->created_at->format('d-m-Y à H:i:s') }}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="fin" class="form-label">Date de fin :</label>
                        <select class="form-select" id="fin" name="fin">
                            <option value="">Choisir une date</option>
                            @foreach($demandes as $demande)
                            @if(!$demande->notif_reponse)
                                <option value="{{ $demande->created_at }}">{{ $demande->created_at->format('d-m-Y à H:i:s') }}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-primary" id="downloadFormBtn">Envoyer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    body {
        background: #EFF1F3;
        min-height: 100vh;
        position: relative;
    }

    .section-50 {
        padding: 50px 0;
    }

    .m-b-50 {
        margin-bottom: 50px;
    }

    .dark-link {
        color: #333;
    }

    .heading-line {
        position: relative;
        padding-bottom: 5px;
    }

    .heading-line:after {
        content: "";
        height: 4px;
        width: 75px;
        background-color: #29B6F6;
        position: absolute;
        bottom: 0;
        left: 0;
    }

    .notification-ui_dd-content {
        margin-bottom: 30px;
    }

    .notification-list {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-pack: justify;
        -ms-flex-pack: justify;
        justify-content: space-between;
        padding: 20px;
        margin-bottom: 7px;
        background: #fff;
        -webkit-box-shadow: 0 3px 10px rgba(0, 0, 0, 0.06);
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.06);
    }

    .notification-list--unread {
        border-left: 2px solid #29B6F6;
    }

    .notification-list .notification-list_content {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
    }

    .notification-list .notification-list_content .notification-list_detail p {
        margin-bottom: 5px;
        line-height: 1.2;
    }

    .notification-checkbox {
        border-radius: 50%;
        width: 20px;
        height: 20px;
    }
</style>

<script>
    // Sélection des cases à cocher
    // Boutons d'action
    const downloadBtn = document.getElementById('downloadBtn');
    const replyBtn = document.getElementById('replyBtn');

    // Script pour l'affichage de la fenêtre en appuyant sur le bouton "détails"
    document.addEventListener('DOMContentLoaded', function () {
        const detailsButtons = document.querySelectorAll('.detailsBtn');
        const modalTitle = document.getElementById('detailsModalLabel');
        const modalBody = document.querySelector('#detailsModal .modal-body');

        detailsButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                // Récupérer les données de la notification
                const modal = document.getElementById('detailsModal');
                const modalInstance = new bootstrap.Modal(modal);
                const note = button.getAttribute('data-note');
                const presence = button.getAttribute('data-presence');
                const periode = button.getAttribute('data-periode');
                const lieu = button.getAttribute('data-lieu');

                // Afficher les données dans la fenêtre modale
                modalTitle.innerText = "Détails de la participation";
                modalBody.innerHTML = `
                    <p><strong>Session:</strong> ${periode} à ${lieu}</p>
                    <p><strong>Présence:</strong> ${presence}</p>
                    <p><strong>Note:</strong> ${note}</p>
                    <!-- Ajoutez d'autres informations si nécessaire -->
                `;
                modalInstance.show();
            });
        });
    });
</script>


@endsection
