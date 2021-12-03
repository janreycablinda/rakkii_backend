<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobOrder;

class JobOrderController extends Controller
{
    public function job_orders()
    {
        $get = JobOrder::with('customer', 'documents', 'activity_log', 'activity_log.user', 'scope', 'scope.sub_services', 'scope.sub_services.sub_services', 'scope.services.services_type', 'property', 'property.vehicle', 'insurance')->where('is_deleted', false)->get();

        return response()->json($get);
    }

    public function car_in(Request $request)
    {
        $update = JobOrder::where('id', $request->id)->update([
            
        ]);
    }
}
