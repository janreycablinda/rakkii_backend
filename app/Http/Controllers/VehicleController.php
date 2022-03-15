<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;

class VehicleController extends Controller
{
    public function vehicles()
    {
        $get = Vehicle::where('is_deleted', false)->get();

        return response()->json($get);
    }

    public function add_vehicle(Request $request)
    {
        $new = new Vehicle;
        $new->vehicle_name = $request->vehicle_name;
        $new->save();

        return response()->json($new);
    }

    public function delete_vehicle($id)
    {
        $del = Vehicle::where('id', $id)->update([
            'is_deleted' => true
        ]);

        return response()->json(200);
    }
}
