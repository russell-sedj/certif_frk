@extends('layouts.myapp')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <h3 class="m-b-30 heading-line">Demandes d'attestation</h3>
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#demandes">Demandes @if($cpt) ({{$cpt}} en cours) @endif</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#reponses">Réponses</a>
                </li>
            </ul>
            <div class="tab-content">
                <div id="demandes" class="tab-pane fade show active">
                    <div class="notification-ui_dd-content">
                        @foreach($demandes as $demande)
                        <div class="notification-list mt-2 @if(!$demande->prete) notification-list--unread @endif ">
                            <div class="notification-list_content">
                                <div class="notification-list_detail">
                                    <p class="text-muted">Votre demande d'attestation pour le thème <strong>{{ $demande->libelle_demande}}</strong> a été envoyée avec succès!</p>
                                    <p class="text-muted"><small>{{ $demande->created_at }}</small></p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div id="reponses" class="tab-pane fade">
                    <div class="notification-ui_dd-content">
                        @foreach($demandes as $demande)
                        @if($demande->statut=="traitée")
                        <div class="notification-list">
                            <div class="notification-list_content">
                                <div class="notification-list_detail">
                                    <p><b>{{ $demande->theme }}</b></p>
                                    <p class="text-muted">Votre attestation pour le thème {{$demande->libelle_demande}} est prête.</p>
                                    <p class="text-muted"><small>{{ $demande->updated_at}}</small></p>
                                </div>
                            </div>
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>
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

.notification-list .notification-list_content .notification-list_img img {
    height: 48px;
    width: 48px;
    border-radius: 50px;
    margin-right: 20px;
}

.notification-list .notification-list_content .notification-list_detail p {
    margin-bottom: 5px;
    line-height: 1.2;
}
</style>
@endsection
