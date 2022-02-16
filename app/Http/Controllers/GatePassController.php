<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GatePass;
use App\Models\JobOrder;

class GatePassController extends Controller
{
    public function gate_pass_no()
    {
        $get = GatePass::latest('created_at')->first();

        if($get){
            $gate_pass_no = $get->gate_pass_no+1;
        }else{
            $gate_pass_no = 1;
        }
        return response()->json($gate_pass_no);
    }

    public function submit_gatepass(Request $request)
    {
        $new = new GatePass;
        $new->job_order_id = $request->job_order_id;
        $new->user_id = auth()->user()->id;
        $new->save();

        return response()->json(JobOrder::with('gatepass.user', 'payments.user','mail.user', 'purchases.receipts', 'purchases.purchase_items', 'purchases.supplier', 'loa_documents', 'payables', 'customer', 'timeline.services_type', 'timeline.personnel', 'documents', 'activity_log.user', 'scope', 'scope.sub_services', 'scope.sub_services.sub_services', 'scope.services.services_type', 'property', 'property.vehicle', 'insurance')->find($request->job_order_id));
    }

    public function delete_gatepass(Request $request)
    {
        $del = GatePass::where('id', $request->id)->delete();

        return response()->json(JobOrder::with('gatepass.user', 'payments.user','mail.user', 'purchases.receipts', 'purchases.purchase_items', 'purchases.supplier', 'loa_documents', 'payables', 'customer', 'timeline.services_type', 'timeline.personnel', 'documents', 'activity_log.user', 'scope', 'scope.sub_services', 'scope.sub_services.sub_services', 'scope.services.services_type', 'property', 'property.vehicle', 'insurance')->find($request->job_order_id));
    }
}
