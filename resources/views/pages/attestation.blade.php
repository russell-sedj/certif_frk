<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Document</title>
</head>
<body>
    <header class="">
        <div class="flex justify-between">
            <div>
                <img class="w-24 h-24" src="{{ asset('images/ministere.png') }}" alt="">
            </div>
            <div>
                <img class="w-24 h-24" src="{{ asset('images/certi_trust.png') }}" alt="">
            </div>
            <div>
                <img class="w-24 h-24" src="{{ asset('images/logo_60ans.jpg') }}" alt="">
            </div>
        </div>
    </header>
</body>
</html>

