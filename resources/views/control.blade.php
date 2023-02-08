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
    <script src="//js.pusher.com/3.1/pusher.min.js"></script>

</head>

<body class="p-10 bg-slate-900 font-poppins text-white">
    <div class="grid grid-cols-3 gap-4">
        <div
            class="col-span-3 rounded-tr-full rounded-bl-full rounded-tl-[200px] rounded-br-[200px] p-8 flex justify-center bg-white">
            <img src="{{ asset('assets/img/aeroponik.png') }}" alt="">
        </div>
        <a href="{{ route('monitoring') }}"
            class="col-span-3 md:col-span-1 self-center text-lg font-bold h-full underline text-center bg-slate-500 rounded-lg p-6 m-3 hover:drop-shadow-mdhover:bg-slate-800 flex">
            <div class="m-auto">
                Monitoring
            </div>
        </a>
        <a href="{{ route('dashboard') }}"
            class="col-span-3 md:col-span-1 self-center text-lg font-bold h-full underline text-center bg-slate-500 rounded-lg p-6 m-3 hover:drop-shadow-md hover:bg-slate-800 flex">
            <div class="m-auto">
                Dashboard
            </div>
        </a>
        <a href="{{ route('control') }}"
            class="col-span-3 md:col-span-1 self-center text-lg font-bold h-full underline text-center bg-slate-700 rounded-lg p-6 m-3 hover:drop-shadow-md hover:bg-slate-800 flex">
            <div class="m-auto">
                Control
            </div>
        </a>
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
                {{ $target_tanaman ?? 'Pakcoy' }}
            </p>
        </div>
        <div class="col-span-12 md:col-span-4 bg-slate-800 rounded-lg p-8">
            <p class="text-lg font-bold flex justify-center">
                Status Pompa Siram :
            </p>
            <div class="text-lg font-bold flex justify-center">
                <span id='status_pompa_siram'>
                    {{ $data->pompa_siram->value == '1' ? 'Hidup' : 'Mati' }}
                </span>
            </div>
            <div class="text-lg font-bold flex justify-center">
                <button type="button"
                    class="my-3 inline-block px-6 py-2.5 bg-blue-600 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out"
                    onclick="onRelay('pompa_siram')">
                    Hidupkan
                </button>
            </div>
        </div>
        <div class="col-span-12 md:col-span-4 bg-slate-800 rounded-lg p-8">
            <p class="text-lg font-bold flex justify-center">
                Status Pompa Nutrisi :
            </p>
            <div class="text-lg font-bold flex justify-center">
                <span id='status_pompa_nutrisi'>
                    {{ $data->pompa_nutrisi->value == '1' ? 'Hidup' : 'Mati' }}
                </span>
            </div>
            <div class="text-lg font-bold flex justify-center">
                <button type="button"
                    class="my-3 inline-block px-6 py-2.5 bg-blue-600 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out"
                    onclick="onRelay('pompa_nutrisi')">
                    Hidupkan
                </button>
            </div>
        </div>
        <div class="col-span-12 md:col-span-4 bg-slate-800 rounded-lg p-8">
            <p class="text-lg font-bold flex justify-center">
                Status Pompa Mixer Nutrisi :
            </p>
            <div class="text-lg font-bold flex justify-center">
                <span id='status_pompa_mixer'>
                    {{ $data->pompa_mixer->value == '1' ? 'Hidup' : 'Mati' }}
                </span>
            </div>
            <div class="text-lg font-bold flex justify-center">
                <button type="button"
                    class="my-3 inline-block px-6 py-2.5 bg-blue-600 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out"
                    onclick="onRelay('pompa_mixer')">
                    Hidupkan
                </button>
            </div>
        </div>
    </div>
    <script>
        function onRelay(jenis) {
            $.post(`{{ route('store.data', '') }}/${jenis}`, {
                "value": 1
            }, function(data, code) {
                if(code == 'success')
                    alert('Pompa akan segera di hidupkan')
            });
        }
    </script>
</body>

</html>
