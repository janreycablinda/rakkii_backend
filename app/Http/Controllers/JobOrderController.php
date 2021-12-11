<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobOrder;
use App\Models\JobOrderActivityLog;
use App\Models\Timeline;
use App\Models\JobOrderScopeServices;
use App\Models\JobOrderScopeSubServices;

class JobOrderController extends Controller
{
    public function job_orders()
    {
        $get = JobOrder::with('customer', 'timeline.services_type', 'timeline.personnel', 'documents', 'activity_log', 'activity_log.user', 'scope', 'scope.sub_services', 'scope.sub_services.sub_services', 'scope.services.services_type', 'property', 'property.vehicle', 'insurance')->where('is_deleted', false)->get();

        return response()->json($get);
    }

    public function start_working(Request $request)
    {
        $update = JobOrder::where('id', $request->id)->update([
            'car_in' => $request->date_in,
            'car_out' => $request->date_out,
            'status' => 'inprogress'
        ]);

        foreach($request->group_work_type as $group){
            $timeline = new Timeline;
            $timeline->job_order_id = $request->id;
            $timeline->services_type_id = $group['services_type_id'];
            $timeline->user_id = $request->user_id;
            $timeline->status = 'pending';
            $timeline->save();
        }
        
        return response()->json(JobOrder::with('customer', 'timeline.services_type', 'timeline.personnel', 'documents', 'activity_log', 'activity_log.user', 'scope', 'scope.sub_services', 'scope.sub_services.sub_services', 'scope.services.services_type', 'property', 'property.vehicle', 'insurance')->find($request->id));
    }

    public function start_working_timeline(Request $request)
    {
        $update = Timeline::where('id', $request->timeline_id)->update([
            'date_start' => $request->date_start,
            'personnel_id' => $request->personnel_id,
            'panels' => $request->panels,
            'commitment_date' => $request->commitment_date,
            'status' => 'inprogress'
        ]);

        return response()->json(JobOrder::with('customer', 'timeline.services_type', 'timeline.personnel', 'documents', 'activity_log', 'activity_log.user', 'scope', 'scope.sub_services', 'scope.sub_services.sub_services', 'scope.services.services_type', 'property', 'property.vehicle', 'insurance')->where('id', $request->job_order_id)->first());
    }

    public function update_status_job_order(Request $request)
    {
        $update = JobOrder::where('id', $request->id)->update([
            'status' => $request->status
        ]);

        $activitylog = new JobOrderActivityLog;
        $activitylog->job_order_id = $request->id;
        $activitylog->user_id = $request->user_id;
        $activitylog->activity = 'Marked estimates as '. $request->status;
        $activitylog->save();

        return response()->json(JobOrder::with('customer', 'timeline.services_type', 'timeline.personnel', 'documents', 'activity_log', 'activity_log.user', 'scope', 'scope.sub_services', 'scope.sub_services.sub_services', 'scope.services.services_type', 'property', 'property.vehicle', 'insurance')->find($request->id));
    }

    public function complete_timeline(Request $request)
    {
        $update = Timeline::where('id', $request->timeline_id)->update([
            'date_done' => $request->date_done,
            'remarks' => $request->remarks,
            'status' => 'completed'
        ]);

        return response()->json(JobOrder::with('customer', 'timeline.services_type', 'timeline.personnel', 'documents', 'activity_log', 'activity_log.user', 'scope', 'scope.sub_services', 'scope.sub_services.sub_services', 'scope.services.services_type', 'property', 'property.vehicle', 'insurance')->where('id', $request->job_order_id)->first());
    }

    public function update_timeline(Request $request)
    {
        $update = Timeline::where('id', $request->id)->update([
            'date_start' => $request->date_start,
            'personnel_id' => $request->personnel_id,
            'panels' => $request->panels,
            'commitment_date' => $request->commitment_date,
            'date_done' => $request->date_done,
            'remarks' => $request->remarks,
        ]);

        return response()->json(JobOrder::with('customer', 'timeline.services_type', 'timeline.personnel', 'documents', 'activity_log', 'activity_log.user', 'scope', 'scope.sub_services', 'scope.sub_services.sub_services', 'scope.services.services_type', 'property', 'property.vehicle', 'insurance')->where('id', $request->job_order_id)->first());
    }

    public function find_timeline($id)
    {
        $get = Timeline::find($id);

        return response()->json($get);
    }

    public function find_job_order($id)
    {
        $get = JobOrder::with('customer', 'timeline.services_type', 'timeline.personnel', 'documents', 'activity_log', 'activity_log.user', 'scope', 'scope.sub_services', 'scope.sub_services.sub_services', 'scope.services.services_type', 'property', 'property.vehicle', 'insurance')->where('id', $id)->first();

        return response()->json($get);
    }

    public function update_job_order(Request $request)
    {
        $update = JobOrder::where('id', $request->id)->update([
            'customer_id' => $request->customer_id,
            'date' => $request->date,
            'insurance_id' => $request->insurance_id,
            'vehicle_id' => $request->vehicle_id,
        ]);

        $services = $request->services;

        $delete = JobOrderScopeServices::where('job_order_id', $request->id)->delete();

        foreach($services as $serv){
            $newservices = new JobOrderScopeServices;
            $newservices->job_order_id = $request->id;
            $newservices->services_id = $serv['services_id'];
            $newservices->labor_fee = $serv['labor_fee'];
            $newservices->parts_fee = $serv['parts_fee'];
            $newservices->save();
            if($serv['sub_services'] != ''){
                foreach($serv['sub_services'] as $sub){
                    $newsubservices = new JobOrderScopeSubServices;
                    $newsubservices->job_order_scope_services_id = $newservices->id;
                    $newsubservices->sub_services_id = $sub['sub_services_id'];
                    $newsubservices->labor_fee = $sub['labor_fee'];
                    $newsubservices->parts_fee = $sub['parts_fee'];
                    $newsubservices->save();
                }
            }
        }

        return ;
    }
}
