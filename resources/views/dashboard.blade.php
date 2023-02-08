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

    {{-- untuk chart --}}
    {{-- https://tailwindcomponents.com/component/chart-widget --}}
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js" defer></script>
    <style>
        @import url(https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/5.3.45/css/materialdesignicons.min.css);
        @import url(https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.css);
    </style>
</head>

<body class="p-10 bg-slate-900 font-poppins text-white">
    <div class="grid grid-cols-3 gap-4">
        <div
            class="col-span-3 rounded-tr-full rounded-bl-full rounded-tl-[200px] rounded-br-[200px] p-8 flex justify-center bg-white">
            <img src="{{ asset('assets/img/aeroponik.png') }}" alt="">
        </div>
        <a href="{{ route('monitoring') }}"
            class="col-span-3 md:col-span-1 text-lg font-bold h-full underline text-center bg-slate-700 rounded-lg m-3 hover:drop-shadow-md  self-center flex hover:bg-slate-800">
            <div class="m-auto">
                Monitoring
            </div>
        </a>
        <a href="{{ route('dashboard') }}"
            class="col-span-3 md:col-span-1 self-center text-lg font-bold h-full underline text-center bg-slate-500 rounded-lg p-6 m-3 hover:drop-shadow-mdhover:bg-slate-800 flex">
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
        <div class="col-span-12 bg-slate-800 rounded-lg p-8">
            <p class="text-lg font-bold flex justify-center">
            </p>
            <p class="text-lg font-bold flex justify-center">
            <div class="min-w-screen min-h-screen bg-gray-900 flex items-center justify-center px-5 py-5">
                <div class="bg-gray-800 text-gray-500 rounded shadow-xl py-5 px-5 w-full" x-data="{ chartData: chartData() }"
                    x-init="chartData.fetch()">
                    <div class="flex flex-wrap items-end">
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold leading-tight">Chart Sensor</h3>
                        </div>
                        <div class="relative" @click.away="chartData.showDropdown=false">
                            <button class="text-xs hover:text-gray-300 h-6 focus:outline-none"
                                @click="chartData.showDropdown=!chartData.showDropdown">
                                {{-- <span x-text="chartData.options[chartData.selectedOption].label"></span><i
                                    class="ml-1 mdi mdi-chevron-down"></i> --}}
                            </button>
                            <div class="bg-gray-700 shadow-lg rounded text-sm absolute top-auto right-0 min-w-full w-32 z-30 mt-1 -mr-3"
                                x-show="chartData.showDropdown" style="display: none;"
                                x-transition:enter="transition ease duration-300 transform"
                                x-transition:enter-start="opacity-0 translate-y-2"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition ease duration-300 transform"
                                x-transition:leave-start="opacity-100 translate-y-0"
                                x-transition:leave-end="opacity-0 translate-y-4">
                                <span
                                    class="absolute top-0 right-0 w-3 h-3 bg-gray-700 transform rotate-45 -mt-1 mr-3"></span>
                                <div class="bg-gray-700 rounded w-full relative z-10 py-1">
                                    <ul class="list-reset text-xs">
                                        <template x-for="(item,index) in chartData.options">
                                            <li class="px-4 py-2 hover:bg-gray-600 hover:text-white transition-colors duration-100 cursor-pointer"
                                                :class="{ 'text-white': index == chartData.selectedOption }"
                                                @click="chartData.selectOption(index);chartData.showDropdown=false">
                                                <span x-text="item.label"></span>
                                            </li>
                                        </template>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <canvas id="chart" class="w-full"></canvas>
                    </div>
                </div>
            </div>
            </p>
        </div>
    </div>
    {{-- untuk chart --}}
    {{-- https://tailwindcomponents.com/component/chart-widget --}}
    <script>
        Number.prototype.comma_formatter = function() {
            return this.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
        }

        let chartData = function() {
            return {
                date: 'today',
                options: [{
                        label: 'Today',
                        value: 'today',
                    },
                    {
                        label: 'Last 7 Days',
                        value: '7days',
                    }
                ],
                showDropdown: false,
                selectedOption: 0,
                selectOption: function(index) {
                    this.selectedOption = index;
                    this.date = this.options[index].value;
                    this.renderChart();
                },
                data: null,
                fetch: function() {
                    // fetch('https://cdn.jsdelivr.net/gh/swindon/fake-api@master/tailwindAlpineJsChartJsEx1.json')
                    fetch('{{ route('dashboard.json') }}')
                        .then(res => res.json())
                        .then(res => {
                            this.data = res;
                            this.renderChart();
                        })
                },
                renderChart: function() {
                    let c = false;

                    Chart.helpers.each(Chart.instances, function(instance) {
                        if (instance.chart.canvas.id == 'chart') {
                            c = instance;
                        }
                    });

                    if (c) {
                        c.destroy();
                    }

                    let ctx = document.getElementById('chart').getContext('2d');

                    let chart = new Chart(ctx, {
                        type: "line",
                        data: {
                            labels: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
                            datasets: [
                                {
                                    label: "Suhu",
                                    backgroundColor: "rgba(102, 126, 234, 0.25)",
                                    borderColor: "rgba(102, 126, 234, 1)",
                                    pointBackgroundColor: "rgba(102, 126, 234, 1)",
                                    data: this.data.suhu,
                                },
                                {
                                    label: "Kelembaban",
                                    backgroundColor: "rgba(237, 100, 166, 0.25)",
                                    borderColor: "rgba(237, 100, 166, 1)",
                                    pointBackgroundColor: "rgba(237, 100, 166, 1)",
                                    data: this.data.kelembaban,
                                },
                                {
                                    label: "pH",
                                    backgroundColor: "rgba(249, 115, 22,0.25)",
                                    borderColor: "rgba(194, 65, 12, 1)",
                                    pointBackgroundColor: "rgba(154, 52, 18, 1)",
                                    data: this.data.ph,
                                },
                                {
                                    label: "tinggi_bak_air",
                                    backgroundColor: "rgba(102, 126, 234, 0.25)",
                                    borderColor: "rgba(102, 126, 234, 1)",
                                    pointBackgroundColor: "rgba(102, 126, 234, 1)",
                                    data: this.data.tinggi_bak_air,
                                },
                                {
                                    label: "tinggi_nutrisi_a",
                                    backgroundColor: "rgba(237, 100, 166, 0.25)",
                                    borderColor: "rgba(237, 100, 166, 1)",
                                    pointBackgroundColor: "rgba(237, 100, 166, 1)",
                                    data: this.data.tinggi_nutrisi_a,
                                },
                                {
                                    label: "tinggi_nutrisi_b",
                                    backgroundColor: "rgba(249, 115, 22,0.25)",
                                    borderColor: "rgba(194, 65, 12, 1)",
                                    pointBackgroundColor: "rgba(154, 52, 18, 1)",
                                    data: this.data.tinggi_nutrisi_b,
                                },
                            ],
                        },
                        layout: {
                            padding: {
                                right: 10
                            }
                        },
                        options: {
                            scales: {
                                yAxes: [{
                                    gridLines: {
                                        display: false
                                    },
                                    ticks: {
                                        callback: function(value, index, array) {
                                            return value > 1000 ? ((value < 1000000) ? value /
                                                1000 + 'K' : value / 1000000 + 'M') : value;
                                        }
                                    }
                                }]
                            }
                        }
                    });
                }
            }
        }
    </script>
</body>

</html>
