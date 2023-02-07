<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    public function index()
    {
        $data = (object)[
            'suhu'              => Sensor::where('type', 'suhu')->latest()->first() ?? (object)["value" => "--", "updated_at" => null],
            'kelembaban'        => Sensor::where('type', 'kelembaban')->latest()->first() ?? (object)["value" => "--", "updated_at" => null],
            'ph'                => Sensor::where('type', 'ph')->latest()->first() ?? (object)["value" => "--", "updated_at" => null],
            'tinggi_bak_air'    => Sensor::where('type', 'tinggi_bak_air')->latest()->first() ?? (object)["value" => "--", "updated_at" => null],
            'tinggi_nutrisi_a'  => Sensor::where('type', 'tinggi_nutrisi_a')->latest()->first() ?? (object)["value" => "--", "updated_at" => null],
            'tinggi_nutrisi_b'  => Sensor::where('type', 'tinggi_nutrisi_b')->latest()->first() ?? (object)["value" => "--", "updated_at" => null],
        ];

        return view('monitoring', compact('data'));
    }
}
