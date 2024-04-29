@extends('layouts.myapp')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <h3 class="m-b-30 heading-line">Demandes d'attestation</h3>
            <div class="mt-3 d-flex justify-content-end">
                <button class="btn btn-primary" id="downloadBtn" data-bs-toggle="modal" data-bs-target="#downloadModal">Télécharger des attestations</button>
                <button class="btn btn-success ms-2" disabled id="responseBtn" data-bs-toggle="modal" data-bs-target="#responseModal">Envoyer des réponses</button>
            </div>
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#demandes_sent">En attente @if($demandes_sent->count())({{$demandes_sent->count()}})@endif</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#demandes_downloaded">En cours de traitement @if($demandes_downloaded->count())({{$demandes_downloaded->count()}}) @endif</a>
                </li>
            </ul>
            <div class="tab-content">
                <div id="demandes_sent" class="tab-pane fade show active">
                    <div class="notification-ui_dd-content">
                        @if($demandes_sent->count()==0)
                        <div class="notification-list mt-2">
                            <div class="notification-list_content">
                                <div class="notification-list_detail d-flex align-items-center justify-content-between">
                                    <div>
                                        <p class="text-muted">
                                            Aucune nouvelle demande.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        @foreach($demandes_sent as $demande_sent)
                        <div class="notification-list mt-2">
                            <div class="notification-list_content">
                                <div class="notification-list_detail d-flex align-items-center justify-content-between">
                                    <div>
                                        <p><b>{{ $demande_sent->user->name}} {{ $demande_sent->user->surname}}</b></p>
                                        <p class="text-muted">
                                        Nouvelle demande d'attestation pour le thème : <strong>{{$demande_sent->libelle_demande}}</strong>.
                                        </p>
                                        <p class="text-muted"><small>{{ $demande_sent->created_at }}</small></p>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <button class="btn btn-info btn-sm ms-2 mt-3 detailsBtn" 
                                    data-toggle="modal" data-target="#detailsModal"
                                    data-presence="@if(!$demande_sent->sessionproUser->nb_presence){{0}}@else{{$demande_sent->sessionproUser->nb_presence}}@endif/{{$demande_sent->sessionpro->presences->count()}}"
                                    data-note="{{ $demande_sent->sessionproUser->note}}"
                                    data-lieu="@if($demande_sent->sessionpro->lieu->adresse) {{$demande_sent->sessionpro->lieu->adresse}}, @endif {{$demande_sent->sessionpro->lieu->ville}} "
                                    data-periode="{{$demande_sent->sessionpro->date_debut}} - {{$demande_sent->sessionpro->date_fin}}">Détails</button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div id="demandes_downloaded" class="tab-pane fade">
                    <div class="notification-ui_dd-content">
                    @if($demandes_downloaded->count()==0)
                        <div class="notification-list mt-2">
                            <div class="notification-list_content">
                                <div class="notification-list_detail d-flex align-items-center justify-content-between">
                                    <div>
                                        <p class="text-muted">
                                            Aucune demande en cours de traitement.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        @foreach($demandes_downloaded as $demande_downloaded)
                        <div class="notification-list">
                            <div class="notification-list_content">
                            <div class="notification-list_detail d-flex align-items-center justify-content-between">
                                    <div>
                                        <p><b>{{ $demande_downloaded->user->name}} {{ $demande_downloaded->user->surname}}</b></p>
                                        <p class="text-muted">
                                         Demande d'attestation pour le thème : <strong>{{$demande_downloaded->libelle_demande}}</strong>.<br>
                                         Reçue le: <strong>{{$demande_downloaded->created_at->format('d F Y à H:i:s')}}</strong><br>
                                         Attestation téléchargée le: <strong>{{$demande_downloaded->updated_at->format('d F Y à H:i:s')}}</strong>
                                        </p>
                                    </div>
                                </div>
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
<div class="modal fade" id="downloadModal" tabindex="-1" aria-labelledby="downloadModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="downloadModalLabel">Période</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="downloadForm" method="POST" action="{{route('demandes.download')}}">
                    @csrf
                    <div class="mb-3">
                    <label for="periode" class="form-label">Durée :</label>
                   <select class="form-select" id="periode" name="periode" required>
                   <option disabled selected value="">Choisir une période</option>
                   <option value="journee">Une journée</option>
                   <option value="plage">Plus d'une journée</option>
                   </select>
                    </div>
                    <div id="plageJournee" style="display: none;">
                    <div class="mb-3">
                        <label for="debut" class="form-label">Date de début :</label>
                        <select class="form-select" id="debut" name="debut">
                            <option disabled selected value="">Choisir une date</option>
                            @foreach($dates_sent as $date_sent)
                                <option value="{{ $date_sent['min'] }}">{{ $date_sent['min']->format('l d F Y') }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="fin" class="form-label">Date de fin :</label>
                        <select class="form-select" id="fin" name="fin">
                            <option disabled selected value="">Choisir une date</option>
                            @foreach($dates_sent as $date_sent)
                            @if($date_sent['min']!=$date_sent['max'])
                                <option value="{{ $date_sent['max'] }}">{{ $date_sent['max']->format('l d F Y')}}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                    </div>
                    <div id="journee" style="display: none;">
                    <div class="mb-3">
                        <label for="jour" class="form-label">Date :</label>
                        <select class="form-select" id="jour" name="jour">
                            <option disabled selected value="">Choisir une date</option>
                            @foreach($dates_sent as $date_sent)
                                <option value="{{ $date_sent['min'] }}">{{ $date_sent['min']->format('l d F Y') }}</option>
                            @endforeach
                        </select>
                    </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="downloadFormBtn">Valider</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="responseModal" tabindex="-1" aria-labelledby="responseModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="responseModalLabel">Période</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="responseForm" method="POST" action="{{route('demandes.lastupdate')}}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="periode_response" class="form-label">Durée :</label>
                        <select class="form-select" id="periode_response" name="periode">
                            <option disabled selected value="">Choisir une période</option>
                            <option value="journee">Une journée</option>
                            <option value="plage">Plus d'une journée</option>
                        </select>
                    </div>
                   <div id="plageJournee_response" style="display: none;">
                    <div class="mb-3">
                      <label for="debut" class="form-label">Date de début :</label>
                        <select class="form-select" id="debut" name="debut">
                        <option disabled selected value="">Choisir une date</option>
                          @foreach($dates_downloaded as $date_downloaded)
                            <option value="{{ $date_downloaded['min']}}">{{ $date_downloaded['min']->format('l d F Y') }}</option>
                           @endforeach
                              </select>
                            </div>
                            <div class="mb-3">
                        <label for="fin" class="form-label">Date de fin :</label>
                        <select class="form-select" id="fin" name="fin">
                       <option disabled selected value="">Choisir une date</option>
                        @foreach($dates_downloaded as $date_downloaded)
                        @if($date_downloaded['min']!=$date_downloaded['max'])
                         <option value="{{ $date_downloaded['max'] }}">{{ $date_downloaded['max']->format('l d F Y')}}</option>
                         @endif 
                         @endforeach
                       </select>
                    </div>
                    </div>
                    <div id="journee_response" style="display: none;">
                    <div class="mb-3">
                        <label for="jour" class="form-label">Date :</label>
                        <select class="form-select" id="jour" name="jour">
                            <option disabled selected value="">Choisir une date</option>
                            @foreach($dates_downloaded as $date_downloaded)
                                <option value="{{ $date_downloaded['min'] }}">{{ $date_downloaded['min']->format('l d F Y') }}</option>
                            @endforeach
                        </select>
                    </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="responseFormBtn">Valider</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
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

    .notification-list .notification-list_content {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
    }

    .notification-list .notification-list_content .notification-list_detail p {
        margin-bottom: 5px;
        line-height: 1.2;
    }

</style>

<script>
   document.addEventListener('DOMContentLoaded', function () {
    function toggleButtons() {
        const downloadBtn = document.getElementById('downloadBtn');
        const responseBtn = document.getElementById('responseBtn');
        const activeTab = document.querySelector('.nav-link.active');

        if (activeTab.hash === '#demandes_sent') {
            downloadBtn.disabled = false;
            responseBtn.disabled = true;
        } else if (activeTab.hash === '#demandes_downloaded') {
            downloadBtn.disabled = true;
            responseBtn.disabled = false;
        }
    }
    document.querySelectorAll('.nav-link').forEach(function(link) {
        link.addEventListener('click', toggleButtons);
    });
});

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
    document.addEventListener('DOMContentLoaded', function () {
    const periodeSelects = document.querySelectorAll('#periode, #periode_response');
    const plageJourneeDivs = document.querySelectorAll('#plageJournee, #plageJournee_response');
    const journeeDivs = document.querySelectorAll('#journee, #journee_response');

    periodeSelects.forEach(function (select) {
        select.addEventListener('change', function () {
            const value = this.value;
            if (value === 'journee') {
                plageJourneeDivs.forEach(function (div) {
                    div.style.display = 'none';
                });
                journeeDivs.forEach(function (div) {
                    div.style.display = 'block';
                });
            } else if (value === 'plage') {
                plageJourneeDivs.forEach(function (div) {
                    div.style.display = 'block';
                });
                journeeDivs.forEach(function (div) {
                    div.style.display = 'none';
                });
            } else {
                plageJourneeDivs.forEach(function (div) {
                    div.style.display = 'none';
                });
                journeeDivs.forEach(function (div) {
                    div.style.display = 'none';
                });
            }
        });
    });
});
</script>
@if (session('toast_message'))
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false" id="myToast">
        <div class="toast-header">
            <svg class="bd-placeholder-img rounded me-2" width="20" height="20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" preserveAspectRatio="xMidYMid slice" focusable="false"><rect width="100%" height="100%" fill="#007aff"></rect></svg>
            <strong class="me-auto">Notification</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            {!!session('toast_message') !!}
        </div>
    </div>
</div>
<script>
    // Afficher le toast lorsque la page est chargée
    document.addEventListener('DOMContentLoaded', function() {
        var myToast = new bootstrap.Toast(document.getElementById('myToast'));
        myToast.show();

        // Rediriger vers la route demandesAgent.index lors du clic sur le toast
        var toastLink = document.querySelector('.toast-link');
        toastLink.addEventListener('click', function(event) {
            event.preventDefault(); // Empêcher le comportement par défaut du lien
            window.location.href = this.href; // Rediriger vers l'URL du lien
        });
    });
</script>
@endif

@endsection
