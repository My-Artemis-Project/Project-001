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
        <a href="{{route('monitoring')}}"
            class="col-span-3 md:col-span-1 self-center text-lg font-bold h-full underline text-center bg-slate-700 rounded-lg p-6 m-3 hover:drop-shadow-mdhover:bg-slate-800 flex">
            <div class="m-auto">
                Monitoring
            </div>
        </a>
        <a href="{{route('dashboard')}}"
            class="col-span-3 md:col-span-1 self-center text-lg font-bold h-full underline text-center bg-slate-500 rounded-lg p-6 m-3 hover:drop-shadow-md hover:bg-slate-800 flex">
            <div class="m-auto">
                Dashboard
            </div>
        </a>
        <a href="{{route('control')}}"
            class="col-span-3 md:col-span-1 self-center text-lg font-bold h-full underline text-center bg-slate-500 rounded-lg p-6 m-3 hover:drop-shadow-md hover:bg-slate-800 flex">
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
                Suhu :
            </p>
            <div class="text-lg font-bold flex justify-center">
                <span id='suhu'>
                    {{ $data->suhu->value ?? '--' }}
                </span>
                &nbsp;
                <span> &deg;C </span>
            </div>
            <div class="text-lg font-bold flex justify-center">
                <span id='suhu_updated_at'>
                    @php
                        if ($data->suhu->updated_at) {
                            $date = Carbon\Carbon::parse($data->suhu->updated_at)->locale('id');
                            $date->settings(['formatFunction' => 'translatedFormat']);
                            echo $date->format('j F Y, H:i:s');
                        }
                    @endphp
                </span>
            </div>
        </div>
        <div class="col-span-12 md:col-span-4 bg-slate-800 rounded-lg p-8">
            <p class="text-lg font-bold flex justify-center">
                Kelembaban :
            </p>
            <div class="text-lg font-bold flex justify-center">
                <span id='kelembaban'>
                    {{ $data->kelembaban->value ?? '--' }}
                </span>
                &nbsp;
                <span> % RH </span>
            </div>
            <div class="text-lg font-bold flex justify-center">
                <span id='kelembaban_updated_at'>
                    @php
                        if ($data->kelembaban->updated_at) {
                            $date = Carbon\Carbon::parse($data->kelembaban->updated_at)->locale('id');
                            $date->settings(['formatFunction' => 'translatedFormat']);
                            echo $date->format('j F Y, H:i:s');
                        }
                    @endphp
                </span>
            </div>
        </div>
        <div class="col-span-12 md:col-span-4 bg-slate-800 rounded-lg p-8">
            <p class="text-lg font-bold flex justify-center">
                pH ( potential of hydrogen ) :
            </p>
            <div class="text-lg font-bold flex justify-center">
                <span id='ph'>
                    {{ $data->ph->value ?? '--' }}
                </span>
            </div>
            <div class="text-lg font-bold flex justify-center">
                <span id='ph_updated_at'>
                    @php
                        if ($data->ph->updated_at) {
                            $date = Carbon\Carbon::parse($data->ph->updated_at)->locale('id');
                            $date->settings(['formatFunction' => 'translatedFormat']);
                            echo $date->format('j F Y, H:i:s');
                        }
                    @endphp
                </span>
            </div>
        </div>
        <div class="col-span-12 md:col-span-4 bg-slate-800 rounded-lg p-8">
            <p class="text-lg font-bold flex justify-center">
                Tinggi cairan di bak air :
            </p>
            <div class="text-lg font-bold flex justify-center">
                <span id='tinggi_bak_air'>
                    {{ $data->tinggi_bak_air->value ?? '--' }}
                </span>
                &nbsp;
                <span> cm </span>
            </div>
            <div class="text-lg font-bold flex justify-center">
                <span id='tinggi_bak_air_updated_at'>
                    @php
                        if ($data->tinggi_bak_air->updated_at) {
                            $date = Carbon\Carbon::parse($data->tinggi_bak_air->updated_at)->locale('id');
                            $date->settings(['formatFunction' => 'translatedFormat']);
                            echo $date->format('j F Y, H:i:s');
                        }
                    @endphp
                </span>
            </div>
        </div>
        <div class="col-span-12 md:col-span-4 bg-slate-800 rounded-lg p-8">
            <p class="text-lg font-bold flex justify-center">
                Tinggi cairan di nutrisi A :
            </p>
            <div class="text-lg font-bold flex justify-center">
                <span id='tinggi_nutrisi_a'>
                    {{ $data->tinggi_nutrisi_a->value ?? '--' }}
                </span>
                &nbsp;
                <span> cm </span>
            </div>
            <div class="text-lg font-bold flex justify-center">
                <span id='tinggi_nutrisi_a_updated_at'>
                    @php
                        if ($data->tinggi_nutrisi_a->updated_at) {
                            $date = Carbon\Carbon::parse($data->tinggi_nutrisi_a->updated_at)->locale('id');
                            $date->settings(['formatFunction' => 'translatedFormat']);
                            echo $date->format('j F Y, H:i:s');
                        }
                    @endphp
                </span>
            </div>
        </div>
        <div class="col-span-12 md:col-span-4 bg-slate-800 rounded-lg p-8">
            <p class="text-lg font-bold flex justify-center">
                Tinggi cairan di nutrisi B :
            </p>
            <div class="text-lg font-bold flex justify-center">
                <span id='tinggi_nutrisi_b'>
                    {{ $data->tinggi_nutrisi_b->value ?? '--' }}
                </span>
                &nbsp;
                <span> cm </span>
            </div>
            <div class="text-lg font-bold flex justify-center">
                <span id='tinggi_nutrisi_b_updated_at'>
                    @php
                        if ($data->tinggi_nutrisi_b->updated_at) {
                            $date = Carbon\Carbon::parse($data->tinggi_nutrisi_b->updated_at)->locale('id');
                            $date->settings(['formatFunction' => 'translatedFormat']);
                            echo $date->format('j F Y, H:i:s');
                        }
                    @endphp
                </span>
            </div>
        </div>
    </div>
    <script>
        var pusher = new Pusher('0d7d9bdb9fadb39a989a', {
            cluster: 'ap1',
            encrypted: true,
            authEndpoint: '/broadcasting/auth',
            disableStats: true,
            auth: {
                headers: {
                    'X-CSRF-Token': '{{ csrf_token() }}', // CSRF token
                }
            }
        });
        var channel = pusher.subscribe('data-sensor-updated');
        channel.bind('App\\Events\\DataSensorUpdated', function(data) {
            date = new Date(data.updated_at)
            $('#' + data.type).html(data.value)
            $('#' + data.type + '_updated_at').html(data.updated_at)
        })
    </script>
</body>

</html>
