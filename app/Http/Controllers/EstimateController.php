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
use App\Mail\MailEstimate;
use URL;
use App\Models\MailTrack;
use App\Models\Payable;
use Carbon\Carbon;
use App\Models\JobOrderLoaDocument;
use App\Models\JobOrderActivityLog;


class EstimateController extends Controller
{
    public function estimates()
    {
        $get = Estimate::with('mail.user', 'customer', 'loa_documents', 'documents', 'activity_log', 'activity_log.user', 'scope', 'scope.sub_services', 'scope.sub_services.sub_services', 'scope.services', 'property', 'property.vehicle', 'insurance')->where('is_deleted', false)->get();

        return response()->json($get);
    }

    public function estimate_count()
    {
        $get = Estimate::latest()->first();

        return response()->json($get->estimate_no+1);
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

        $file_upload = $request->file('files');
        $pic = $request->file('pic');
        if($documents){
            foreach($documents as $key => $docs){
                if($docs['files'] != ''){
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
                
            }
        }

        $activitylog = new EstimateActivityLog;
        $activitylog->estimate_id = $new->id;
        $activitylog->user_id = $request->user_id;
        $activitylog->activity = 'Created the estimate';
        $activitylog->save();
        
        return response()->json($new->load('mail.user','customer', 'documents', 'loa_documents', 'activity_log', 'activity_log.user', 'scope', 'scope.sub_services', 'scope.sub_services.sub_services', 'scope.services', 'property', 'property.vehicle', 'insurance'));
    }

    public function update_estimate(Request $request)
    {
        $update = Estimate::where('id', $request->id)->update([
            'customer_id' => $request->customer_id,
            'date' => $request->date,
            'insurance_id' => $request->insurance_id,
            'vehicle_id' => $request->vehicle_id,
        ]);

        $services = $request->services;

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

        return ;
    }

    public function add_estimate_save_send(Request $request)
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
            if(count($serv['sub_services']) > 0){
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
        if($documents){
            foreach($documents as $key => $docs){
                if($docs['files'] != ''){
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
                
            }
        }
        

        $activitylog = new EstimateActivityLog;
        $activitylog->estimate_id = $new->id;
        $activitylog->user_id = $request->user_id;
        $activitylog->activity = 'Created the estimate';
        $activitylog->save();

        $url = URL::temporarySignedRoute(
            'uploader', now()->addMinutes(4320), ['estimate' => $new->id]
        );
        $split = explode("/", $url);
        $custom_url = 'http://localhost:8080/uploader/' . $split[6];

        $data["email"]=$request->form_email;
        $data["client_name"]='janreycablinda';
        $data["messages"]=$request->form_message;
        $data["link"]=$custom_url;
        $data["estimate"]=$new->load('customer', 'documents', 'activity_log', 'activity_log.user', 'scope', 'scope.sub_services', 'scope.sub_services.sub_services', 'scope.services', 'property', 'property.vehicle', 'insurance');
        $data["subject"]='RAKKII AUTO SERVICES';
 
        $pdf = PDF::loadView('test', $data);

        $getDocs = Document::where('estimate_id', $new->id)->orderBy('id', 'ASC')->get();
        
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

            $mail_track = new MailTrack;
            $mail_track->estimate_id = $new->id;
            $mail_track->email = $request->form_email;
            $mail_track->action = 'was sent message to';
            $mail_track->user_id = $request->user_id;
            $mail_track->save();
            
        }catch(JWTException $exception){
            $this->serverstatuscode = "0";
            $this->serverstatusdes = $exception->getMessage();
        }

        
        return response()->json($new->load('mail.user', 'customer', 'loa_documents', 'documents', 'activity_log', 'activity_log.user', 'scope', 'scope.sub_services', 'scope.sub_services.sub_services', 'scope.services', 'property', 'property.vehicle', 'insurance'));

        // $data["email"]=$request->email;
        // $data["client_name"]='janreycablinda';
        // $data["messages"]=$request->form_message;
        // $data["completed"]='test';
        // $data["subject"]='RAKKII AUTO SERVICES';
 
        // $pdf = PDF::loadView('test', $data);

        // $path = public_path('img/upload/CIP-1637383815.pdf');
        // $path2 = public_path('img/upload/CIP-1637383815.pdf');
        
        // try{
        //     Mail::send('emails.mail', $data, function($message)use($data,$path,$path2) {
        //     $message->to($data["email"], $data["client_name"])
        //     ->subject($data["subject"])
        //     ->attachData($pdf->output(), "report.pdf")
        //     ->attach($path, [
        //         'as' => 'name.pdf',
        //         'mime' => 'application/pdf',
        //     ])
        //     ->attach($path2, [
        //         'as' => 'name2.pdf',
        //         'mime' => 'application/pdf',
        //     ]);
        //     });
        // }catch(JWTException $exception){
        //     $this->serverstatuscode = "0";
        //     $this->serverstatusdes = $exception->getMessage();
        // }
        // if (Mail::failures()) {
        //      $this->statusdesc  =   "Error sending mail";
        //      $this->statuscode  =   "0";
 
        // }else{
 
        //    $this->statusdesc  =   "Message sent Succesfully";
        //    $this->statuscode  =   "1";
        // }
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

        return response()->json(Estimate::with('mail.user', 'customer', 'loa_documents', 'documents', 'activity_log', 'activity_log.user', 'scope', 'scope.sub_services', 'scope.sub_services.sub_services', 'scope.services', 'property', 'property.vehicle', 'insurance')->where('id', $request->id)->first());
    }

    public function send_estimate_to_loa(Request $request)
    {
        $estimate = Estimate::with('mail.user', 'customer', 'loa_documents', 'documents', 'activity_log', 'activity_log.user', 'scope', 'scope.sub_services', 'scope.sub_services.sub_services', 'scope.services', 'property', 'property.vehicle', 'insurance')->where('id', $request->id)->first();

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

        $getDocs = Document::where('estimate_id', $request->id)->orderBy('id', 'ASC')->get();
        
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

            $mail_track = new MailTrack;
            $mail_track->estimate_id = $estimate->id;
            $mail_track->email = $request->email;
            $mail_track->action = 'was sent message to';
            $mail_track->user_id = $request->user_id;
            $mail_track->save();
            
        }catch(JWTException $exception){
            $this->serverstatuscode = "0";
            $this->serverstatusdes = $exception->getMessage();
        }

        
        return response()->json(Estimate::with('mail.user', 'customer', 'loa_documents', 'documents', 'activity_log', 'activity_log.user', 'scope', 'scope.sub_services', 'scope.sub_services.sub_services', 'scope.services', 'property', 'property.vehicle', 'insurance')->where('id', $request->id)->first());
    }

    public function find_estimates($id)
    {
        $get = Estimate::with('mail.user', 'customer', 'documents', 'scope.sub_services.sub_services', 'scope.sub_services', 'scope.services', 'property', 'property.vehicle', 'insurance')->find($id);
       
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
        $get = Estimate::with('mail.user', 'customer', 'loa_documents', 'documents', 'activity_log', 'activity_log.user', 'scope', 'scope.sub_services', 'scope.sub_services.sub_services', 'scope.services', 'property', 'property.vehicle', 'insurance')->where('id', $request->id)->first();

        $job_order = new JobOrder;
        $job_order->customer_id = $get->customer_id;
        $job_order->date = Carbon::now()->format('Y-m-d');
        $job_order->insurance_id = $get->insurance_id;
        $job_order->vehicle_id = $get->vehicle_id;
        $job_order->status = 'pending';
        $job_order->save();

        $payables = new Payable;
        $payables->job_order_id = $job_order->id;
        $payables->total_repair_cost = 0;
        $payables->policy_deductible = 0;
        $payables->betterment = 0;
        $payables->discount = 0;
        $payables->net = 0;
        $payables->save();

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

        foreach($get->loa_documents as $loa){
            $newLoaDocs = new JobOrderLoaDocument;
            $newLoaDocs->job_order_id = $job_order->id;
            $newLoaDocs->file_name = $loa['file_name'];
            $newLoaDocs->save();
        }

        if($request->status == ''){
            $update = Estimate::where('id', $request->id)->update([
                'is_deleted' => true
            ]);
        }else{
            $update = Estimate::where('id', $request->id)->update([
                'status' => $request->status
            ]);

            $activitylog = new EstimateActivityLog;
            $activitylog->estimate_id = $get->id;
            $activitylog->user_id = $request->user_id;
            $activitylog->activity = 'has converted to job order #JO-000' . $job_order->job_order_no . ' and save as '. $request->status;
            $activitylog->save();

            $activitylog = new JobOrderActivityLog;
            $activitylog->job_order_id = $job_order->id;
            $activitylog->user_id = $request->user_id;
            $activitylog->activity = 'Created the job order using #EST-000' . $get->estimate_no;
            $activitylog->save();
        }
        
        return response()->json(['data' =>Estimate::with('mail.user', 'customer', 'loa_documents', 'documents', 'activity_log', 'activity_log.user', 'scope', 'scope.sub_services', 'scope.sub_services.sub_services', 'scope.services', 'property', 'property.vehicle', 'insurance')->where('id', $request->id)->first(), 'status' => $request->status]);
    }
}
