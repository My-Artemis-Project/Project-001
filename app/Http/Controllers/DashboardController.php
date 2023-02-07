<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use Illuminate\Http\Request;

class DashboardController extends Controller
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
            'pompa_siram'       => Sensor::where('type', 'pompa_siram')->latest()->first() ?? (object)["value" => "--", "updated_at" => null],
            'pompa_nutrisi'     => Sensor::where('type', 'pompa_nutrisi')->latest()->first() ?? (object)["value" => "--", "updated_at" => null],
            'pompa_mixer'       => Sensor::where('type', 'pompa_mixer')->latest()->first() ?? (object)["value" => "--", "updated_at" => null],
        ];
        return view('dashboard', compact('data'));
    }
    public function json(){
        $data = (object)[
            'suhu'              => Sensor::where('type', 'suhu')->orderBy('created_at', 'desc')->limit(10)->get()->pluck('value') ?? (object)[],
            'kelembaban'        => Sensor::where('type', 'kelembaban')->orderBy('created_at', 'desc')->limit(10)->get()->pluck('value') ?? (object)[],
            'ph'                => Sensor::where('type', 'ph')->orderBy('created_at', 'desc')->limit(10)->get()->pluck('value') ?? (object)[],
            'tinggi_bak_air'    => Sensor::where('type', 'tinggi_bak_air')->orderBy('created_at', 'desc')->limit(10)->get()->pluck('value') ?? (object)[],
            'tinggi_nutrisi_a'  => Sensor::where('type', 'tinggi_nutrisi_a')->orderBy('created_at', 'desc')->limit(10)->get()->pluck('value') ?? (object)[],
            'tinggi_nutrisi_b'  => Sensor::where('type', 'tinggi_nutrisi_b')->orderBy('created_at', 'desc')->limit(10)->get()->pluck('value') ?? (object)[],
            'pompa_siram'       => Sensor::where('type', 'pompa_siram')->orderBy('created_at', 'desc')->limit(10)->get()->pluck('value') ?? (object)[],
            'pompa_nutrisi'     => Sensor::where('type', 'pompa_nutrisi')->orderBy('created_at', 'desc')->limit(10)->get()->pluck('value') ?? (object)[],
            'pompa_mixer'       => Sensor::where('type', 'pompa_mixer')->orderBy('created_at', 'desc')->limit(10)->get()->pluck('value') ?? (object)[],
        ];
        return response()->json($data);

    }
}
