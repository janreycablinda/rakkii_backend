<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Personnel;

class PersonnelController extends Controller
{
    public function personnels()
    {
        $get = Personnel::with('personnel_type')->where('is_deleted', false)->get();

        return response()->json($get);
    }

    public function add_personnel(Request $request)
    {
        $new = new Personnel;
        $new->name = $request->name;
        $new->phone = $request->phone;
        $new->address = $request->address;
        $new->personnel_type_id = $request->personnel_type_id;
        $new->save();

        return response()->json($new);
    }

    public function delete_personnel($id)
    {
        $del = Personnel::where('id', $id)->update([
            'is_deleted' => true
        ]);

        return response()->json(200);
    }
}
