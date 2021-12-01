<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Services;

class ServicesController extends Controller
{
    public function services()
    {
        $get = Services::with('sub_services', 'services_type')->where('is_deleted', false)->get();

        return response()->json($get);
    }

    public function add_services(Request $request)
    {
        $new = new Services;
        $new->services_type_id = $request->services_type_id;
        $new->services_name = $request->services_name;
        $new->save();

        return response()->json($new->load('services_type', 'sub_services'));
    }

    public function find_services($id)
    {
        $find = Services::with('sub_services', 'services_type')->find($id);

        return response()->json($find);
    }
}
