<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unit;

class UnitController extends Controller
{
    public function units()
    {
        $units = Unit::where('is_deleted', false)->get();

        return response()->json($units);
    }

    public function add_unit(Request $request)
    {
        $add = new Unit;
        $add->name = $request->name;
        $add->save();

        return response()->json($add);
    }

    public function delete_unit($id)
    {
        $delete = Unit::where('id', $id)->update([
            'is_deleted' => true
        ]);

        return response()->json(200);
    }
}
