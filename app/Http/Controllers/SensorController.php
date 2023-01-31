<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use Illuminate\Http\Request;

class SensorController extends Controller
{
    public function get($type){
        return response()->json(
            Sensor::where('type',$type)->latest()->first()
        );
    }
    public function store(Request $request, $type)
    {
        $sensor = new Sensor;
        $sensor->type = $type;
        $sensor->value = $request->value;
        $sensor->store();

        return response()->json($sensor);
    }
    public function getList(){
        return response()->json(
            Sensor::all()
        );
    }
}
