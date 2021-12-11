<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PersonnelType;

class PersonnelTypeController extends Controller
{
    public function personnel_types()
    {
        $get = PersonnelType::where('is_deleted', false)->get();

        return response()->json($get);
    }

    public function add_personnel_type(Request $request)
    {
        $new = new PersonnelType;
        $new->name = $request->name;
        $new->save();

        return response()->json($new);
    }
}
