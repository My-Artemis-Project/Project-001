<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use Illuminate\Http\Request;

class ControlController extends Controller
{
    public function index()
    {
        $data = (object)[
            'pompa_siram'   => Sensor::where('type', 'pompa_siram')->latest()->first() ?? (object)["value" => "--", "updated_at" => null],
            'pompa_nutrisi' => Sensor::where('type', 'pompa_nutrisi')->latest()->first() ?? (object)["value" => "--", "updated_at" => null],
            'pompa_mixer'   => Sensor::where('type', 'pompa_mixer')->latest()->first() ?? (object)["value" => "--", "updated_at" => null],
        ];
        return view('control', compact('data'));
    }
}
