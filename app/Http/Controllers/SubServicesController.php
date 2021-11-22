<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubServices;
use App\Models\Services;

class SubServicesController extends Controller
{
    public function add_sub_services(Request $request)
    {
        $new = new SubServices;
        $new->services_id = $request->services_id;
        $new->services_name = $request->services_name;
        $new->save();
        if($new){
            return response()->json(Services::with('sub_services', 'services_type')->where('is_deleted', false)->get());
        }
        
    }
}
