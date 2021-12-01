<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Insurance;

class InsuranceController extends Controller
{
    public function insurance()
    {
        $get = Insurance::where('is_deleted', false)->get();

        return response()->json($get);
    }

    public function add_insurance(Request $request)
    {
        $new = new Insurance;
        $new->insurance_name = $request->insurance_name;
        $new->insurance_type = $request->insurance_type;
        $new->contact_person = $request->contact_person;
        $new->phone = $request->phone;
        $new->email = $request->email;
        $new->address = $request->address;
        $new->tin = $request->tin;
        $new->save();

        return response()->json($new);
    }

    public function delete_insurance($id)
    {
        $del = Insurance::where('id', $id)->update([
            'is_deleted' => true
        ]);

        return response()->json(200);
    }
}
