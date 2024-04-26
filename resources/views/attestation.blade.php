<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.7.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
    <title>Document</title>
</head>

<style>
    main {
        font-family: "Times New Roman", Times, serif;
    }

    .background {
        background-image: url("{{ asset('images/attestation_background.png') }}");
        background-repeat: no-repeat;
        background-size: 100% 100%;
    }
</style>

<button id="downloadButton">Télécharger</button><span></span>

@foreach ($demandes as $demande)
<div class="background ms-5 me-8 mt-8" id="attestation{{$demande->id}}">
    <div class="p-20">
        <body>
            <header>
                <div class="flex justify-between">
                    <div>
                        <img class="h-20" src="{{ asset('images/ministere.png') }}" alt="">
                    </div>
                    <div>
                        <img class="h-20" src="{{ asset('images/certi_trust.png') }}" alt="">
                        <p class="text-[10px]">Version 2005</p>
                    </div>
                    <div class="flex items-start">
                        <div class="w-56 text-right">
                            <p class="text-xs font-bold">DIRECTION GENERALE DU TRESOR ET DE LA COMPTABILITE PUBLIQUE</p>
                            <div class="text-[11px]">
                                <p>Tel: 21 30 19 37 - Fax: 21 30 01 57</p>
                                <p>BP: 40 COTONOU - ROUTE DE L'AEROPORT</p>
                                <p>www.tresorbenin.bj</p>
                            </div>
                        </div>
                        <img class="h-24" src="{{ asset('images/logo_60ans.jpg') }}" alt="">
                    </div>
                </div>
            </header>
    
            <?php
               // $date_debut = DateTime::createFromFormat('l, j F Y', $demande->sessionpro->date_debut);
                //$date_debut_modif = $date_debut->format('l, j');
            ?>
    
            <main>
                <p class="mt-10 font-bold text-xs">N&deg;________________/MEP/DGTCP/DFPT/SP/SDC</p>
                <br>
                <h1 class="font-extrabold  text-4xl text-center">ATTESTATION DE PARTICIPATION</h1>
                <br>
                    <div class="text-xl">
                        <p class="text-center">Le Directeur général du Trésor et de la Comptabilité publique soussigné, atteste que 
                            <span class="font-bold">@if($demande->user->sexe=="M") Monsieur @else Madame @endif {{ $demande->user->name}} {{ $demande->user->surname}}</span>, agent du Trésor public, a participé au séminaire de 
                                formation sur "<span class="font-bold">{{ $demande->sessionpro->formation->theme}}</span>" organisé par la Direction de la Formation Professionnelle 
                                du Trésor du {{ \Carbon\Carbon::parse($demande->sessionpro->date_debut)->format('l d') }} au {{ \Carbon\Carbon::parse($demande->sessionpro->date_fin)->format('l d F Y') }} à {{ $demande->sessionpro->lieu->ville }}.
                        </p>
                        <br>
                        <p class="text-center">En foi de quoi la présente attestation lui est délivrée pour servir et valoir ce que de droit.</p>
                        <br>
                        <p class="font-bold text-center">Fait à Cotonou, le </p>
                        <br>
                        <div class="flex justify-between">
                            <div class="text-center">
                                <p>Le Directeur de la Formation <br>Professionnelle du Trésor,</p>
                                <p class="font-bold mt-5">@if($dfpt){{$dfpt->surname}} {{$dfpt->name}}@endif</p>
                            </div>
                            <div class="text-center">
                                <p>Le Directeur général du Trésor et <br>de la Comptabilité publique,</p>
                                <p class="font-bold mt-5">@if($dgtcp){{$dgtcp->surname}} {{$dgtcp->name}}@endif</p>
                            </div>
                        </div>
                    </div>
            </main>
        </body>
    </div>
</div>
@endforeach
</html>

<script>
    document.getElementById('downloadButton').addEventListener('click', function() {
        const demandes = @json($demandes);
        const debut = @json($debut);
        const fin = @json($fin);
        const zip = new JSZip();
        const zipFilename = `attestations_du_${debut}_au_${fin}.zip`; // Utilisation de backticks pour l'interpolation
        const promises = [];

        demandes.forEach(demande => {
            const element = document.querySelector(`#attestation${demande.id}`);

            // Options pour la conversion en PDF.
            const options = {
                filename: `${demande.sessionpro.formation.theme}_${demande.user.name}_${demande.user.surname}.pdf`,
                image: { type: 'jpeg', quality: 1 },
                html2canvas: { scale: 2 },
                jsPDF: { unit: 'mm', format: 'a4', orientation: 'landscape'},
            };

            const promise = html2pdf().set(options).from(element).toPdf().get('pdf').then(function(pdf) {
                zip.file(options.filename, pdf.output('blob'));
            });

            promises.push(promise);
        });

        Promise.all(promises).then(function() {
            zip.generateAsync({ type: 'blob' }).then(function(content) {
                // Télécharger le fichier ZIP
                saveAs(content, zipFilename);
                
                // Après le téléchargement réussi du fichier ZIP
                fetch('{{ route("demandes.update") }}', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ debut: debut, fin: fin })
                })
                .then(handleResponse)
                .then(() => {
                    window.location.href = '{{ route("demandesSup.index") }}';
                })
                .catch(handleError);
            });
        });
    });

    // Fonction pour gérer la réponse de la requête fetch
    function handleResponse(response) {
        if (!response.ok) {
            throw new Error('Erreur lors de la requête AJAX');
        }
    }

    // Fonction pour gérer les erreurs
    function handleError(error) {
        console.error('Erreur lors de la requête AJAX :', error);
    }
</script>

