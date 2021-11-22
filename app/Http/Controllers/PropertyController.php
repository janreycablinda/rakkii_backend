<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CarProperty;

class PropertyController extends Controller
{
    public function find_property($id)
    {
        $find = CarProperty::with('vehicle')->where('customer_id', $id)->where('is_deleted', false)->get();

        return response()->json($find);
    }

    public function add_property(Request $request)
    {
        $new = new CarProperty;
        $new->customer_id = $request->customer_id;
        $new->vehicle_id = $request->vehicle_id;
        $new->plate_no = $request->plate_no;
        $new->save();

        return response()->json($new->load('vehicle'));
    }
}
