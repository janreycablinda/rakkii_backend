<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobOrder;
use App\Models\JobOrderActivityLog;
use App\Models\Timeline;
use App\Models\JobOrderScopeServices;
use App\Models\JobOrderScopeSubServices;
use App\Models\Payable;
use App\Models\JobOrderMailTrack;
use App\Models\JobOrderDocument;
use URL;
use PDF;
use Mail;
use App\Models\OtherExpense;

class JobOrderController extends Controller
{
    public function job_orders()
    {
        $get = JobOrder::with('billings.billing_payment', 'billings.insurance', 'billings.job_order', 'billings.customer', 'gatepass.user', 'payments.user', 'mail.user', 'purchases.receipts', 'purchases.purchase_items', 'purchases.supplier', 'loa_documents', 'payables', 'customer', 'timeline.services_type', 'timeline.personnel', 'documents', 'activity_log.user', 'scope', 'scope.sub_services', 'scope.sub_services.sub_services', 'scope.services.services_type', 'property', 'property.vehicle', 'insurance')->where('is_deleted', false)->get();

        return response()->json($get);
    }

    public function start_working(Request $request)
    {
        $update = JobOrder::where('id', $request->id)->update([
            'car_in' => $request->date_in,
            'car_out' => $request->date_out,
            'status' => 'Inprogress'
        ]);

        foreach($request->group_work_type as $group){
            $timeline = new Timeline;
            $timeline->job_order_id = $request->id;
            $timeline->services_type_id = $group['services_type_id'];
            $timeline->user_id = $request->user_id;
            $timeline->status = 'pending';
            $timeline->save();
        }
        
        return response()->json(JobOrder::with('gatepass.user', 'payments.user', 'mail.user', 'purchases.receipts', 'purchases.purchase_items', 'purchases.supplier', 'loa_documents', 'payables', 'customer', 'timeline.services_type', 'timeline.personnel', 'documents', 'activity_log', 'activity_log.user', 'scope', 'scope.sub_services', 'scope.sub_services.sub_services', 'scope.services.services_type', 'property', 'property.vehicle', 'insurance')->find($request->id));
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

        return response()->json(JobOrder::with('gatepass.user', 'payments.user', 'mail.user', 'purchases.receipts', 'purchases.purchase_items', 'purchases.supplier', 'loa_documents', 'payables', 'customer', 'timeline.services_type', 'timeline.personnel', 'documents', 'activity_log', 'activity_log.user', 'scope', 'scope.sub_services', 'scope.sub_services.sub_services', 'scope.services.services_type', 'property', 'property.vehicle', 'insurance')->where('id', $request->job_order_id)->first());
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

        return response()->json(JobOrder::with('gatepass.user', 'payments.user', 'mail.user', 'purchases.receipts', 'purchases.purchase_items', 'purchases.supplier', 'loa_documents', 'payables', 'customer', 'timeline.services_type', 'timeline.personnel', 'documents', 'activity_log', 'activity_log.user', 'scope', 'scope.sub_services', 'scope.sub_services.sub_services', 'scope.services.services_type', 'property', 'property.vehicle', 'insurance')->find($request->id));
    }

    public function complete_timeline(Request $request)
    {
        $update = Timeline::where('id', $request->timeline_id)->update([
            'date_done' => $request->date_done,
            'remarks' => $request->remarks,
            'status' => 'completed'
        ]);

        return response()->json(JobOrder::with('gatepass.user', 'payments.user', 'mail.user', 'purchases.receipts', 'purchases.purchase_items', 'purchases.supplier', 'loa_documents', 'payables', 'customer', 'timeline.services_type', 'timeline.personnel', 'documents', 'activity_log', 'activity_log.user', 'scope', 'scope.sub_services', 'scope.sub_services.sub_services', 'scope.services.services_type', 'property', 'property.vehicle', 'insurance')->where('id', $request->job_order_id)->first());
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

        return response()->json(JobOrder::with('gatepass.user', 'payments.user', 'mail.user', 'purchases.receipts', 'purchases.purchase_items', 'purchases.supplier', 'loa_documents', 'payables', 'customer', 'timeline.services_type', 'timeline.personnel', 'documents', 'activity_log', 'activity_log.user', 'scope', 'scope.sub_services', 'scope.sub_services.sub_services', 'scope.services.services_type', 'property', 'property.vehicle', 'insurance')->where('id', $request->job_order_id)->first());
    }

    public function find_timeline($id)
    {
        $get = Timeline::find($id);

        return response()->json($get);
    }

    public function find_job_order($id)
    {
        $get = JobOrder::with('other_expenses', 'gatepass.user', 'payments.user', 'mail.user', 'purchases.receipts', 'purchases.purchase_items.item', 'purchases.purchase_items.unit', 'purchases.supplier', 'loa_documents', 'payables', 'customer', 'timeline.services_type', 'timeline.personnel', 'documents', 'activity_log', 'activity_log.user', 'scope', 'scope.sub_services', 'scope.sub_services.sub_services.services', 'scope.services.services_type', 'property', 'property.vehicle', 'insurance')->where('id', $id)->first();

        return response()->json($get);
    }

    public function find_customer_job_order($id)
    {
        $get = JobOrder::with('gatepass.user', 'payments.user', 'mail.user', 'purchases.receipts', 'purchases.purchase_items.item', 'purchases.supplier', 'loa_documents', 'payables', 'customer', 'timeline.services_type', 'timeline.personnel', 'documents', 'activity_log', 'activity_log.user', 'scope', 'scope.sub_services', 'scope.sub_services.sub_services', 'scope.services.services_type', 'property', 'property.vehicle', 'insurance')->where('customer_id', $id)->get();

        return response()->json($get);
    }

    public function update_job_order(Request $request)
    {
        $update = JobOrder::where('id', $request->id)->update([
            'customer_id' => $request->customer_id,
            'agent_id' => $request->agent_id,
            'date' => $request->date,
            'insurance_id' => $request->insurance_id,
            'vehicle_id' => $request->vehicle_id,
        ]);

        $update = Payable::where('job_order_id', $request->id)->update([
            'total_repair_cost' => $request->total_repair_cost,
            'policy_deductible' => $request->policy_deductible,
            'betterment' => $request->betterment,
            'discount' => $request->discount,
            'net' => $request->net,
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

        if($request->other_expenses){
            $del = OtherExpense::where('job_order_id', $request->id)->delete();
            foreach($request->other_expenses as $other){
                $expense = new OtherExpense;
                $expense->job_order_id = $request->id;
                $expense->expenses_type_id = $other['expenses_type_id'];
                $expense->amount = $other['amount'];
                $expense->save();
            }
        }

        return ;
    }

    public function send_job_order_estimate_to_loa(Request $request)
    {
        $estimate = JobOrder::with('mail.user', 'purchases.receipts', 'purchases.purchase_items', 'purchases.supplier', 'loa_documents', 'payables', 'customer', 'timeline.services_type', 'timeline.personnel', 'documents', 'activity_log', 'activity_log.user', 'scope', 'scope.sub_services', 'scope.sub_services.sub_services', 'scope.services.services_type', 'property', 'property.vehicle', 'insurance')->where('id', $request->id)->first();

        $url = URL::temporarySignedRoute(
            'uploader', now()->addMinutes(4320), ['estimate' => $request->id]
        );
        $split = explode("/", $url);
        $custom_url = 'http://localhost:8080/uploader/' . $split[6];

        $data["email"]=$request->email;
        $data["client_name"]='janreycablinda';
        $data["messages"]=$request->message;
        $data["link"]=$custom_url;
        $data["estimate"]=$estimate;
        $data["subject"]='RAKKII AUTO SERVICES';
 
        $pdf = PDF::loadView('test', $data);

        $getDocs = JobOrderDocument::where('job_order_id', $request->id)->orderBy('id', 'ASC')->get();
        
        try{
            Mail::send('emails.mail', $data, function($message)use($data,$getDocs,$pdf) {
                $message->to($data["email"], $data["client_name"])
                        ->subject($data["subject"])
                        ->attachData($pdf->output(), "estimate.pdf");

                if($getDocs){
                    foreach($getDocs as $doc){
                        $path = public_path('img/upload/' . $doc['file_name']);
                        $format = explode(".", $doc['file_name']);
                        if($doc['document_type'] == 'Pictures'){
                            $message->attach($path, [
                                'as' => $doc['document_type'] . '.' . $format[1],
                                'mime' => 'image/jpeg',
                            ]);
                        }else{
                            $message->attach($path, [
                                'as' => $doc['document_type'] . '.' . $format[1],
                                'mime' => 'application/pdf',
                            ]);
                        }
                        
                    }
                }
                
            });

            $mail_track = new JobOrderMailTrack;
            $mail_track->job_order_id = $estimate->id;
            $mail_track->email = $request->email;
            $mail_track->action = 'was sent message to';
            $mail_track->user_id = $request->user_id;
            $mail_track->save();
            
        }catch(JWTException $exception){
            $this->serverstatuscode = "0";
            $this->serverstatusdes = $exception->getMessage();
        }
        
        return response()->json(JobOrder::with('gatepass.user', 'payments.user', 'mail.user', 'purchases.receipts', 'purchases.purchase_items', 'purchases.supplier', 'loa_documents', 'payables', 'customer', 'timeline.services_type', 'timeline.personnel', 'documents', 'activity_log', 'activity_log.user', 'scope', 'scope.sub_services', 'scope.sub_services.sub_services', 'scope.services.services_type', 'property', 'property.vehicle', 'insurance')->where('id', $request->id)->first());
    }

    public function add_purchase(Request $request)
    {
        
    }

    public function job_order_complete(Request $request)
    {
        $update = JobOrder::where('id', $request->id)->update([
            'status' => 'completed'
        ]);

        return response()->json(JobOrder::with('gatepass.user', 'payments.user','mail.user', 'purchases.receipts', 'purchases.purchase_items', 'purchases.supplier', 'loa_documents', 'payables', 'customer', 'timeline.services_type', 'timeline.personnel', 'documents', 'activity_log.user', 'scope', 'scope.sub_services', 'scope.sub_services.sub_services', 'scope.services.services_type', 'property', 'property.vehicle', 'insurance')->find($request->id));
    }

    public function find_job_order_status($id, $property_id, $status)
    {
        if($property_id == 'All' && $status != 'All'){
            $get = JobOrder::with('gatepass.user', 'payments.user','mail.user', 'purchases.receipts', 'purchases.purchase_items', 'purchases.supplier', 'loa_documents', 'payables', 'customer', 'timeline.services_type', 'timeline.personnel', 'documents', 'activity_log.user', 'scope', 'scope.sub_services', 'scope.sub_services.sub_services', 'scope.services.services_type', 'property', 'property.vehicle', 'insurance')->where('customer_id', $id)->where('status', $status)->get();
        }else if($status == 'All' && $property_id != 'All'){
            $get = JobOrder::with('gatepass.user', 'payments.user','mail.user', 'purchases.receipts', 'purchases.purchase_items', 'purchases.supplier', 'loa_documents', 'payables', 'customer', 'timeline.services_type', 'timeline.personnel', 'documents', 'activity_log.user', 'scope', 'scope.sub_services', 'scope.sub_services.sub_services', 'scope.services.services_type', 'property', 'property.vehicle', 'insurance')->where('customer_id', $id)->where('vehicle_id', $property_id)->get();
        }else{
            $get = JobOrder::with('gatepass.user', 'payments.user','mail.user', 'purchases.receipts', 'purchases.purchase_items', 'purchases.supplier', 'loa_documents', 'payables', 'customer', 'timeline.services_type', 'timeline.personnel', 'documents', 'activity_log.user', 'scope', 'scope.sub_services', 'scope.sub_services.sub_services', 'scope.services.services_type', 'property', 'property.vehicle', 'insurance')->where('customer_id', $id)->get();
        }
        
        return response()->json($get);
    }

    public function delete_job_order($id)
    {
        $del = JobOrder::where('id', $id)->update([
            'is_deleted' => true
        ]);

        return response()->json(200);
    }
}
