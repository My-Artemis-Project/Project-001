<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $data = (object)[
            'suhu'              => Sensor::where('type', 'suhu')->latest()->first() ?? '--',
            'kelembaban'        => Sensor::where('type', 'kelembaban')->latest()->first() ?? '--',
            'ph'                => Sensor::where('type', 'ph')->latest()->first() ?? '--',
            'tinggi_bak_air'    => Sensor::where('type', 'tinggi_bak_air')->latest()->first() ?? '--',
            'tinggi_nutrisi_a'  => Sensor::where('type', 'tinggi_nutrisi_a')->latest()->first() ?? '--',
            'tinggi_nutrisi_b'  => Sensor::where('type', 'tinggi_nutrisi_b')->latest()->first() ?? '--',
        ];
        return view('index', compact('data'));
    }
}
