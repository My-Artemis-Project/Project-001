<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    @vite('resources/css/app.css')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"
        integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
</head>

<body class="p-10 bg-slate-900 font-poppins text-white">
    <div class="grid grid-cols-3 gap-4">
        <div
            class="col-span-3 rounded-tr-full rounded-bl-full rounded-tl-[200px] rounded-br-[200px] p-8 flex justify-center bg-white">
            <img src="{{asset('assets/img/aeroponik.png')}}" alt="">
        </div>
        <div
            class="col-span-3 md:col-span-1 text-lg font-bold h-full underline text-center bg-slate-700 rounded-lg m-3 hover:drop-shadow-md  self-center flex hover:bg-slate-800">
            <div class="m-auto">
                Monitoring
            </div>
        </div>
        <div
            class="col-span-3 md:col-span-1 self-center text-lg font-bold h-full underline text-center bg-slate-500 rounded-lg p-6 m-3 hover:drop-shadow-md hover:bg-slate-800 flex">
            <div class="m-auto">
                Riwayat <br> (Comming Soon)
            </div>
        </div>
        <div
            class="col-span-3 md:col-span-1 self-center text-lg font-bold h-full underline text-center bg-slate-500 rounded-lg p-6 m-3 hover:drop-shadow-md hover:bg-slate-800 flex">
            <div class="m-auto">
                Tentang Kami <br> (Comming Soon)
            </div>
        </div>
        <div class="col-span-3 border-2 mt-2 mb-6 rounded-lg">
            <hr>
        </div>
    </div>
    <div class="grid grid-cols-12 gap-4 bg-slate-700 p-3">
        <div class="col-span-12 bg-slate-800 rounded-lg p-8">
            <p class="text-lg font-bold flex justify-center">
                Target tanaman :
            </p>
            <p class="text-lg font-bold flex justify-center">
                {{$target_tanaman??'Pakcoy'}}
            </p>
        </div>
        <div class="col-span-12 md:col-span-4 bg-slate-800 rounded-lg p-8">
            <p class="text-lg font-bold flex justify-center">
                Suhu :
            </p>
            <p class="text-lg font-bold flex justify-center">
                {{$a??'--'}} &deg;C
            </p>
        </div>
        <div class="col-span-12 md:col-span-4 bg-slate-800 rounded-lg p-8">
            <p class="text-lg font-bold flex justify-center">
                Kelembaban :
            </p>
            <p class="text-lg font-bold flex justify-center">
                {{$a??'--'}} % RH
            </p>
        </div>
        <div class="col-span-12 md:col-span-4 bg-slate-800 rounded-lg p-8">
            <p class="text-lg font-bold flex justify-center">
                pH ( potential of hydrogen ) :
            </p>
            <p class="text-lg font-bold flex justify-center">
                {{$a??'--'}}
            </p>
        </div>
        <div class="col-span-12 md:col-span-4 bg-slate-800 rounded-lg p-8">
            <p class="text-lg font-bold flex justify-center">
                Tinggi cairan di bak air :
            </p>
            <p class="text-lg font-bold flex justify-center">
                {{$a??'--'}} cm
            </p>
        </div>
        <div class="col-span-12 md:col-span-4 bg-slate-800 rounded-lg p-8">
            <p class="text-lg font-bold flex justify-center">
                Tinggi cairan di nutrisi A :
            </p>
            <p class="text-lg font-bold flex justify-center">
                {{$a??'--'}} cm
            </p>
        </div>
        <div class="col-span-12 md:col-span-4 bg-slate-800 rounded-lg p-8">
            <p class="text-lg font-bold flex justify-center">
                Tinggi cairan di nutrisi B :
            </p>
            <p class="text-lg font-bold flex justify-center">
                {{$a??'--'}} cm
            </p>
        </div>
    </div>
</body>

</html>