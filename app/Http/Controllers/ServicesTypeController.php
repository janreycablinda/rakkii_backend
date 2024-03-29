<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Services_type;

class ServicesTypeController extends Controller
{
    public function services_type()
    {
        $get = Services_type::where('is_deleted', false)->get();

        return response()->json($get);
    }

    public function add_services_type(Request $request)
    {
        $add = new Services_type;
        $add->name = $request->name;
        $add->save();

        return response()->json($add);
    }

    public function delete_services_type($id)
    {
        $del = Services_type::where('id', $id)->update([
            'is_deleted' => true
        ]);

        return response()->json(200);
    }
}
