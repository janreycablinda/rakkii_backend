<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\Estimate;
use App\Models\Customer;
use App\Models\ScopeOfWorkServices;
use App\Models\ScopeOfWorkSubServices;
use PDF;
use Mail;
use Response;
use DB;
use App\Models\SubServices;
use App\Models\EstimateActivityLog;
use App\Models\JobOrder;
use App\Models\JobOrderScopeServices;
use App\Models\JobOrderScopeSubServices;
use App\Models\JobOrderDocument;

class EstimateController extends Controller
{
    public function estimates()
    {
        $get = Estimate::with('customer', 'documents', 'activity_log', 'activity_log.user', 'scope', 'scope.sub_services', 'scope.sub_services.sub_services', 'scope.services', 'property', 'property.vehicle', 'insurance')->where('is_deleted', false)->get();

        return response()->json($get);
    }

    public function add_estimate(Request $request)
    {

        $new = new Estimate();
        $new->customer_id = $request->customer_id;
        $new->date = $request->date;
        $new->insurance_id = $request->insurance;
        $new->vehicle_id = $request->vehicle_id;
        $new->status = $request->status;
        $new->save();

        $services = json_decode($request->services, true);

        foreach($services as $serv){
            $newservices = new ScopeOfWorkServices;
            $newservices->estimate_id = $new->id;
            $newservices->services_id = $serv['services_id'];
            $newservices->labor_fee = $serv['labor_fee'];
            $newservices->parts_fee = $serv['parts_fee'];
            $newservices->save();
            if($serv['sub_services']){
                foreach($serv['sub_services'] as $sub){
                    if($sub['sub_services_id']){
                        $newsubservices = new ScopeOfWorkSubServices;
                        $newsubservices->scope_of_work_services_id = $newservices->id;
                        $newsubservices->sub_services_id = $sub['sub_services_id'];
                        $newsubservices->labor_fee = $sub['labor_fee'];
                        $newsubservices->parts_fee = $sub['parts_fee'];
                        $newsubservices->save();
                    }
                }
            }
        }

        $documents = json_decode($request->documents, true);

        // return $request->file('files');
        $file_upload = $request->file('files');
        $pic = $request->file('pic');
        foreach($documents as $key => $docs){
            if($docs['prefix'] == 'P'){
                
                foreach($pic as $key2 => $pics){
                    $files = $docs['prefix'] .'-'.time(). $key2 .'.'. $pics->extension();
                    $pics->move(public_path('img/upload'), $files);

                    $newDocs = new Document;
                    $newDocs->customer_id = $request->customer_id;
                    $newDocs->estimate_id = $new->id;
                    $newDocs->file_name = $files;
                    $newDocs->document_type = $docs['document_name'];
                    $newDocs->save();
                }
            }else{
                $files = $docs['prefix'] .'-'.time(). $key .'.'. $file_upload[$key]->extension();
                $file_upload[$key]->move(public_path('img/upload'), $files);

                $newDocs = new Document;
                $newDocs->customer_id = $request->customer_id;
                $newDocs->estimate_id = $new->id;
                $newDocs->file_name = $files;
                $newDocs->document_type = $docs['document_name'];
                $newDocs->save();
            }
        }

        $activitylog = new EstimateActivityLog;
        $activitylog->estimate_id = $new->id;
        $activitylog->user_id = $request->user_id;
        $activitylog->activity = 'Created the estimate';
        $activitylog->save();
        
        return response()->json($new->load('customer', 'documents', 'activity_log', 'activity_log.user', 'scope', 'scope.sub_services', 'scope.sub_services.sub_services', 'scope.services', 'property', 'property.vehicle', 'insurance'));
    }

    public function update_estimate(Request $request)
    {
        $update = Estimate::where('id', $request->id)->update([
            'customer_id' => $request->customer_id,
            'date' => $request->date,
            'insurance_id' => $request->insurance,
            'vehicle_id' => $request->vehicle_id,
            'status' => $request->status,
        ]);

        $services = json_decode($request->services, true);

        $delete = ScopeOfWorkServices::where('estimate_id', $request->id)->delete();

        foreach($services as $serv){
            $newservices = new ScopeOfWorkServices;
            $newservices->estimate_id = $request->id;
            $newservices->services_id = $serv['services_id'];
            $newservices->labor_fee = $serv['labor_fee'];
            $newservices->parts_fee = $serv['parts_fee'];
            $newservices->save();
            if($serv['sub_services'] != ''){
                foreach($serv['sub_services'] as $sub){
                    $newsubservices = new ScopeOfWorkSubServices;
                    $newsubservices->scope_of_work_services_id = $newservices->id;
                    $newsubservices->sub_services_id = $sub['sub_services_id'];
                    $newsubservices->labor_fee = $sub['labor_fee'];
                    $newsubservices->parts_fee = $sub['parts_fee'];
                    $newsubservices->save();
                }
            }
        }

        $documents = json_decode($request->documents, true);

        // return $request->file('files');
        $file_upload = $request->file('files');
        $pic = $request->file('pic');
        foreach($documents as $key => $docs){
            
            if($docs['prefix'] == 'P'){
                // foreach($pic as $key2 => $pics){
                //     $files = $docs['prefix'] .'-'.time(). $key2 .'.'. $pics->extension();
                //     $pics->move(public_path('img/upload'), $files);

                //     // $newDocs = new Document;
                //     // $newDocs->customer_id = $request->customer_id;
                //     // $newDocs->estimate_id = $new->id;
                //     // $newDocs->file_name = $files;
                //     // $newDocs->document_type = $docs['document_name'];
                //     // $newDocs->save();

                //     $update = Document::where('id', $docs['id'])->update([
                //         'customer_id' => $request->customer_id,
                //         'estimate_id' => $request->id,
                //         'file_name' => $files,
                //         'document_type' => $docs['document_name'],
                //     ]);
                // }
            }else{
                
                $files = $docs['prefix'] .'-'.time(). $key .'.'. $file_upload[$key]->extension();
                $file_upload[$key]->move(public_path('img/upload'), $files);

                $update = Document::where('id', $docs['id'])->update([
                    'customer_id' => $request->customer_id,
                    'estimate_id' => $request->id,
                    'file_name' => $files,
                    'document_type' => $docs['document_name'],
                ]);
                
            }
           
        }
    }

    public function add_estimate_save_send(Request $request)
    {
        // $data["email"]=$request->email;
        // $data["client_name"]=$request->name;
        // $data["messages"]=$request->message;
        // $data["completed"]=$request->data;
        // $data["subject"]='Wash&Go';
        $data["email"]='janreycablinda@gmail.com';
        $data["client_name"]='janreycablinda';
        $data["messages"]='test';
        $data["completed"]='test';
        $data["subject"]='RAKKII AUTO SERVICES';
 
        $pdf = PDF::loadView('test', $data);

        $path = public_path('img/upload/CIP-1637383815.pdf');
        $path2 = public_path('img/upload/CIP-1637383815.pdf');
        
        try{
            Mail::send('emails.mail', $data, function($message)use($data,$path,$path2) {
            $message->to($data["email"], $data["client_name"])
            ->subject($data["subject"])
            ->attach($path, [
                'as' => 'name.pdf',
                'mime' => 'application/pdf',
            ])
            ->attach($path2, [
                'as' => 'name2.pdf',
                'mime' => 'application/pdf',
            ]);
            });
        }catch(JWTException $exception){
            $this->serverstatuscode = "0";
            $this->serverstatusdes = $exception->getMessage();
        }
        if (Mail::failures()) {
             $this->statusdesc  =   "Error sending mail";
             $this->statuscode  =   "0";
 
        }else{
 
           $this->statusdesc  =   "Message sent Succesfully";
           $this->statuscode  =   "1";
        }

        // $new = new Estimate();
        // $new->customer_id = $request->customer_id;
        // $new->insurance_type = $request->insurance_type;
        // $new->date = $request->date;
        // $new->insurance = $request->insurance;
        // $new->vehicle_id = $request->vehicle_id;
        // $new->status = $request->status;
        // $new->save();

        // $services = json_decode($request->services, true);

        // foreach($services as $serv){
        //     $newservices = new ScopeOfWorkServices;
        //     $newservices->estimate_id = $new->id;
        //     $newservices->services_id = $serv['id']['value'];
        //     $newservices->labor = $serv['labor_fee'];
        //     $newservices->parts = $serv['parts_fee'];
        //     $newservices->save();
        //     foreach($serv['sub_services'] as $sub){
        //         $newsubservices = new ScopeOfWorkSubServices;
        //         $newsubservices->scope_of_work_services_id = $newservices->id;
        //         $newsubservices->sub_services_id = $sub['id']['value'];
        //         $newsubservices->labor = $sub['labor_fee'];
        //         $newsubservices->parts = $sub['parts_fee'];
        //         $newsubservices->save();
        //     }
        // }

        // if($request->vehicle_or_cr){
        //     $vehicle_or_cr = 'VOC-'.time().'.'. $request->vehicle_or_cr->getClientOriginalExtension();
        //     $request->vehicle_or_cr->move(public_path('img/upload'), $vehicle_or_cr);
        // }else{
        //     $vehicle_or_cr = null;
        // }

        // if($request->drivers_license_or){
        //     $drivers_license_or = 'DL-'.time().'.'. $request->drivers_license_or->getClientOriginalExtension();
        //     $request->drivers_license_or->move(public_path('img/upload'), $drivers_license_or);
        // }else{
        //     $drivers_license_or = null;
        // }
        
        // if($request->police_report_affidavit_accident){
        //     $police_report_affidavit_accident = 'PRAA-'.time().'.'. $request->police_report_affidavit_accident->getClientOriginalExtension();
        //     $request->police_report_affidavit_accident->move(public_path('img/upload'), $police_report_affidavit_accident);
        // }else{
        //     $police_report_affidavit_accident = null;
        // }

        // if($request->comprehensive_insurance){
        //     $comprehensive_insurance = 'CIP-'.time().'.'. $request->comprehensive_insurance->getClientOriginalExtension();
        //     $request->comprehensive_insurance->move(public_path('img/upload'), $comprehensive_insurance);
        // }else{
        //     $comprehensive_insurance = null;
        // }

        // if($request->pictures){
        //     $pictures = 'PIC-'.time().'.'. $request->pictures->getClientOriginalExtension();
        //     $request->pictures->move(public_path('img/upload'), $pictures);
        // }else{
        //     $pictures = null;
        // }

        // if($request->certificate_of_claim){
        //     $certificate_of_claim = 'CONC-'.time().'.'. $request->certificate_of_claim->getClientOriginalExtension();
        //     $request->certificate_of_claim->move(public_path('img/upload'), $certificate_of_claim);
        // }else{
        //     $certificate_of_claim = null;
        // }

        // if($request->trip_ticket){
        //     $trip_ticket = 'TT-'.time().'.'. $request->trip_ticket->getClientOriginalExtension();
        //     $request->trip_ticket->move(public_path('img/upload'), $trip_ticket);
        // }else{
        //     $trip_ticket = null;
        // }

        // if($request->authorization_letter_for_government){
        //     $authorization_letter_for_government = 'AG-'.time().'.'. $request->authorization_letter_for_government->getClientOriginalExtension();
        //     $request->authorization_letter_for_government->move(public_path('img/upload'), $authorization_letter_for_government);
        // }else{
        //     $authorization_letter_for_government = null;
        // }

        // if($request->authorization_letter_for_individual){
        //     $authorization_letter_for_individual = 'AI-'.time().'.'. $request->authorization_letter_for_individual->getClientOriginalExtension();
        //     $request->authorization_letter_for_individual->move(public_path('img/upload'), $authorization_letter_for_individual);
        // }else{
        //     $authorization_letter_for_individual = null;
        // }

        // if($request->request_for_qoutation){
        //     $request_for_qoutation = 'RFQ-'.time().'.'. $request->request_for_qoutation->getClientOriginalExtension();
        //     $request->request_for_qoutation->move(public_path('img/upload'), $request_for_qoutation);
        // }else{
        //     $request_for_qoutation = null;
        // }

        // if($request->mayors_permit){
        //     $mayors_permit = 'MP-'.time().'.'. $request->mayors_permit->getClientOriginalExtension();
        //     $request->mayors_permit->move(public_path('img/upload'), $mayors_permit);
        // }else{
        //     $mayors_permit = null;
        // }

        // if($request->philgeps){
        //     $philgeps = 'PG-'.time().'.'. $request->philgeps->getClientOriginalExtension();
        //     $request->philgeps->move(public_path('img/upload'), $philgeps);
        // }else{
        //     $philgeps = null;
        // }

        // if($request->omnibus){
        //     $omnibus = 'OB-'.time().'.'. $request->omnibus->getClientOriginalExtension();
        //     $request->omnibus->move(public_path('img/upload'), $omnibus);
        // }else{
        //     $omnibus = null;
        // }

        // if($request->tax_clearance){
        //     $tax_clearance = 'TC-'.time().'.'. $request->tax_clearance->getClientOriginalExtension();
        //     $request->tax_clearance->move(public_path('img/upload'), $tax_clearance);
        // }else{
        //     $tax_clearance = null;
        // }

        // $docs = new Document;
        // $docs->customer_id = $request->customer_id;
        // $docs->vehicle_or_cr = $vehicle_or_cr;
        // $docs->drivers_license = $drivers_license_or;
        // $docs->police_report = $police_report_affidavit_accident;
        // $docs->comprehensive_insurance = $comprehensive_insurance;
        // $docs->pictures = $pictures;
        // $docs->certificate_of_claim = $certificate_of_claim;
        // $docs->trip_ticket = $trip_ticket;
        // $docs->authorization_government = $authorization_letter_for_government;
        // $docs->authorization_individual = $authorization_letter_for_individual;
        // $docs->request_for_qoutation = $request_for_qoutation;
        // $docs->mayors_permit = $mayors_permit;
        // $docs->philgeps = $philgeps;
        // $docs->omnibus = $omnibus;
        // $docs->tax_clearance = $tax_clearance;
        // $docs->save();
        
        // return response()->json($new->load('customer'));
    }

    public function update_status_estimate(Request $request)
    {
        $update = Estimate::where('id', $request->id)->update([
            'status' => $request->status
        ]);

        $activitylog = new EstimateActivityLog;
        $activitylog->estimate_id = $request->id;
        $activitylog->user_id = $request->user_id;
        $activitylog->activity = 'Marked estimates as '. $request->status;
        $activitylog->save();

        return response()->json(Estimate::with('customer', 'documents', 'activity_log', 'activity_log.user', 'scope', 'scope.sub_services', 'scope.sub_services.sub_services', 'scope.services', 'property', 'property.vehicle', 'insurance')->where('id', $request->id)->first());
    }

    public function find_estimates($id)
    {
        $get = Estimate::with('customer', 'documents', 'scope.sub_services.sub_services', 'scope.sub_services', 'scope.services', 'property', 'property.vehicle', 'insurance')->find($id);
       
        return response()->json($get);
    }

    public function find_sub_services($id)
    {
        $get = ScopeOfWorkSubServices::where('scope_of_work_services_id', $id)->get();

        return response()->json($get);
    }

    public function sub_services($id)
    {
        $get = SubServices::where('services_id', $id)->get();

        return response()->json($get);
    }

    public function delete_estimate($id)
    {
        $del = Estimate::where('id', $id)->update([
            'is_deleted' => true
        ]);

        return response()->json(200);
    }

    public function convert_estimate(Request $request)
    {
        $get = Estimate::with('customer', 'documents', 'activity_log', 'activity_log.user', 'scope', 'scope.sub_services', 'scope.sub_services.sub_services', 'scope.services', 'property', 'property.vehicle', 'insurance')->where('id', $request->id)->first();

        $job_order = new JobOrder;
        $job_order->customer_id = $get->customer_id;
        $job_order->date = $get->date;
        $job_order->insurance_id = $get->insurance_id;
        $job_order->vehicle_id = $get->vehicle_id;
        $job_order->status = 'pending';
        $job_order->save();

        foreach($get->scope as $serv){
            $newservices = new JobOrderScopeServices;
            $newservices->job_order_id = $job_order->id;
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

        foreach($get->documents as $docs){
            $newDocs = new JobOrderDocument;
            $newDocs->customer_id = $get->customer_id;
            $newDocs->job_order_id = $job_order->id;
            $newDocs->file_name = $docs['file_name'];
            $newDocs->document_type = $docs['document_type'];
            $newDocs->save();
        }

        $update = Estimate::where('id', $request->id)->update([
            'is_deleted' => true
        ]);
        
        return response()->json($request->id);
    }
}
