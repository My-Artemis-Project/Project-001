<?php

namespace App\Http\Controllers;

use App\Events\DataSensorUpdated;
use App\Models\Sensor;
use Illuminate\Http\Request;

class SensorController extends Controller
{
    public function get($type)
    {
        $data = Sensor::where('type', $type)->latest()->first();
        if($data){
            if($data->value == 1)
                return response()->json(1,200);
            }

        return response()->json(0,501);
    }
    public function store(Request $request, $type)
    {
        $sensor = new Sensor;
        $sensor->type = $type;
        $sensor->value = $request->value;
        $sensor->save();

        event(new DataSensorUpdated($sensor->type, $sensor->value,$sensor->updated_at));

        // return $sensor->updated_at;
        return response()->json($sensor);
    }
    public function getList()
    {
        return response()->json(
            Sensor::all()
        );
    }
}
