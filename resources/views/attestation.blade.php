<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Document</title>
</head>

{{-- <body class="m-5 bg-center bg-no-repeat bg-cover" style="background-image: url('{{ asset('images/attestation_bg.png') }}');"> --}}
<body class="m-16">
    <header class="">
        <div class="flex justify-between">
            <div>
                <img class="h-20" src="{{ asset('images/ministere.png') }}" alt="">
            </div>
            <div>
                <img class="h-20" src="{{ asset('images/certi_trust.png') }}" alt="">
                <p class="text-[10px]">Version 2005</p>
            </div>
            <div class="flex items-start">
                <div class="w-52 text-right">
                    <p class="text-xs font-bold">DIRECTION GENERALE DU TRESOR ET DE LA COMPTABILITE PUBLIQUE</p>
                    <div class=" text-[11px]">
                        <p>Tel: 21 30 19 37 - Fax: 21 30 01 57</p>
                        <p>BP: 40 COTONOU - ROUTE DE L'AEROPORT</p>
                        <p>www.tresorbenin.bj</p>
                    </div>
                </div>
                <img class="h-24" src="{{ asset('images/logo_60ans.jpg') }}" alt="">
            </div>
        </div>
    </header>

    <main>
        <p class="mt-10 font-bold text-xs">N&deg;________________/MEP/DGTCP/DFPT/SP/SDC</p>
        <br>
        <h1 class="font-bold text-3xl text-center">ATTESTATION DE PARTICIPATION</h1>
        <br>
        <div class="">
            <p class="text-center">Le Directeur générale du Trésor et de la Comptabilité publique soussigné, atteste que Monsieur
                <span class="font-bold">ADJAGBA Narcicsse Sourou</span>, agent du Trésor public, a participé
                au séminaire de formation sur "l'utilisation du logiciel Gratafact" organisé par la Direction
                par la Direction de la Formation Professionnelle du Trésor du lundi 16 au mercredi 19 aout 2021
                à Parakou.
            </p>
            <br>
            <p class="text-center">En foi de quoi la présente attestation lui est délivrée por servir et valoir ce que de droit.</p>
            <br>
            <p class="font-bold text-center">Fait à Cotonou, le</p>
            <br>
            <div class="flex justify-between">
                <div>
                    <p>Le Directeurde la Formation Professionnelle du Trésor,</p>
                    <p class="font-bold text-right">Rachidi OSSENI</p>
                </div>
                <div>
                    <p>Le Directeur générale du Trésor et de la Comptabilité publique,</p>
                    <p class="font-bold text-right">Oumara KARIMOU ASSOUMA</p>
                </div>
            </div>
        </div>
    </main>
</body>
</html>

