<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\LoaDocument;
use App\Models\Estimate;
use App\Models\EstimateActivityLog;
use App\Models\JobOrderLoaDocument;
use App\Models\JobOrderDocument;
use App\Models\JobOrderActivityLog;
use App\Models\JobOrder;
use App\Models\ActivityLog;

class DocumentController extends Controller
{
    public function document_download($file_name)
    {

        $file= public_path(). '/img/upload/' . $file_name;
        $headers = [
              'Content-Type' => 'application/pdf',
        ];

        return response()->download($file, $file_name, $headers);
    }

    public function find_documents($id){
        $get = Document::where('customer_id', $id)->orderBy('id', 'DESC')->get();

        return response()->json($get);
    }

    public function find_loa_documents($id){
        $get = LoaDocument::where('customer_id', $id)->orderBy('id', 'DESC')->get();

        return response()->json($get);
    }

    public function add_documents(Request $request)
    {
        if($request->document_type == 'Pictures'){
            $documents = $request->file('files');
            foreach($documents as $key => $file){
                $files = 'P-'.time(). $key .'.'. $file->extension();
                $file->move(public_path('img/upload'), $files);

                $new = new Document;
                $new->customer_id = $request->customer_id;
                $new->estimate_id = $request->id;
                $new->file_name = $files;
                $new->document_type = $request->document_type;
                $new->save();
            }
        }else{
            $documents = $request->file('files');
            $files = $request->prefix .'-'.time().'.'. $documents->extension();
            $documents->move(public_path('img/upload'), $files);

            $new = new Document;
            $new->customer_id = $request->customer_id;
            $new->estimate_id = $request->id;
            $new->file_name = $files;
            $new->document_type = $request->document_type;
            $new->save();
        }

        $activitylog = new EstimateActivityLog;
        $activitylog->estimate_id = $request->id;
        $activitylog->user_id = $request->user_id;
        $activitylog->activity = 'has uploaded ' . $request->document_type . ' Document';
        $activitylog->save();

        return response()->json(Estimate::with('mail.user', 'customer', 'loa_documents', 'documents', 'activity_log', 'activity_log.user', 'scope', 'scope.sub_services', 'scope.sub_services.sub_services', 'scope.services', 'property', 'property.vehicle', 'insurance')->where('id', $request->id)->first());
    }

    public function add_job_order_documents(Request $request)
    {
        if($request->document_type == 'Pictures'){
            $documents = $request->file('files');
            foreach($documents as $key => $file){
                $files = 'P-'.time(). $key .'.'. $file->extension();
                $file->move(public_path('img/upload'), $files);

                $new = new JobOrderDocument;
                $new->customer_id = $request->customer_id;
                $new->job_order_id = $request->id;
                $new->file_name = $files;
                $new->document_type = $request->document_type;
                $new->save();
            }
        }else{
            $documents = $request->file('files');
            $files = $request->prefix .'-'.time().'.'. $documents->extension();
            $documents->move(public_path('img/upload'), $files);

            $new = new JobOrderDocument;
            $new->customer_id = $request->customer_id;
            $new->job_order_id = $request->id;
            $new->file_name = $files;
            $new->document_type = $request->document_type;
            $new->save();
        }

        $activitylog = new JobOrderActivityLog;
        $activitylog->job_order_id = $request->id;
        $activitylog->user_id = $request->user_id;
        $activitylog->activity = 'has uploaded ' . $request->document_type . ' Document';
        $activitylog->save();

        return response()->json(JobOrder::with('mail.user', 'loa_documents', 'payables', 'customer', 'timeline.services_type', 'timeline.personnel', 'documents', 'activity_log.user', 'scope', 'scope.sub_services', 'scope.sub_services.sub_services', 'scope.services.services_type', 'property', 'property.vehicle', 'insurance')->where('id', $request->id)->first());
    }

    public function add_loa_documents(Request $request)
    {
        $documents = $request->file('files');
        foreach($documents as $key => $file){
            $files = 'LOA-'.time(). $key .'.'. $file->extension();
            $file->move(public_path('img/upload'), $files);

            $new = new LoaDocument;
            $new->estimate_id = $request->id;
            $new->file_name = $files;
            $new->save();
        }

        $activitylog = new EstimateActivityLog;
        $activitylog->estimate_id = $request->id;
        $activitylog->user_id = $request->user_id;
        $activitylog->activity = 'has uploaded LOA document';
        $activitylog->save();

        return response()->json(Estimate::with('mail.user', 'customer', 'loa_documents', 'documents', 'activity_log', 'activity_log.user', 'scope', 'scope.sub_services', 'scope.sub_services.sub_services', 'scope.services', 'property', 'property.vehicle', 'insurance')->where('id', $request->id)->first());
    }

    public function add_job_order_loa_documents(Request $request)
    {
        $documents = $request->file('files');
        foreach($documents as $key => $file){
            $files = 'LOA-'.time(). $key .'.'. $file->extension();
            $file->move(public_path('img/upload'), $files);

            $new = new JobOrderLoaDocument;
            $new->job_order_id = $request->id;
            $new->file_name = $files;
            $new->save();
        }

        $activitylog = new JobOrderActivityLog;
        $activitylog->job_order_id = $request->id;
        $activitylog->user_id = $request->user_id;
        $activitylog->activity = 'has uploaded LOA document';
        $activitylog->save();

        return response()->json(JobOrder::with('mail.user', 'loa_documents', 'payables', 'customer', 'timeline.services_type', 'timeline.personnel', 'documents', 'activity_log.user', 'scope', 'scope.sub_services', 'scope.sub_services.sub_services', 'scope.services.services_type', 'property', 'property.vehicle', 'insurance')->where('id', $request->id)->first());
    }

    public function delete_document($id, $estimate_id)
    {
        $get = Document::where('id', $id)->first();
        $delete = Document::where('id', $id)->delete();

        $activitylog = new EstimateActivityLog;
        $activitylog->estimate_id = $estimate_id;
        $activitylog->user_id = auth()->user()->id;
        $activitylog->activity = 'deleted '. $get->document_type . ' ('. $get->file_name .')';
        $activitylog->save();

        return response()->json(Estimate::with('mail.user', 'customer', 'loa_documents', 'documents', 'activity_log', 'activity_log.user', 'scope', 'scope.sub_services', 'scope.sub_services.sub_services', 'scope.services', 'property', 'property.vehicle', 'insurance')->where('id', $estimate_id)->first());
    }

    public function delete_job_order_document($id, $estimate_id)
    {
        $delete = JobOrderDocument::where('id', $id)->delete();

        return response()->json(JobOrder::with('mail.user', 'loa_documents', 'payables', 'customer', 'timeline.services_type', 'timeline.personnel', 'documents', 'activity_log.user', 'scope', 'scope.sub_services', 'scope.sub_services.sub_services', 'scope.services.services_type', 'property', 'property.vehicle', 'insurance')->where('id', $estimate_id)->first());
    }

    public function delete_loa_document($id, $estimate_id)
    {
        $get = LoaDocument::where('id', $id)->first();
        $delete = LoaDocument::where('id', $id)->delete();

        $activitylog = new EstimateActivityLog;
        $activitylog->estimate_id = $estimate_id;
        $activitylog->user_id = auth()->user()->id;
        $activitylog->activity = 'deleted LOA DOCUMENTS ('. $get->file_name .')';
        $activitylog->save();

        return response()->json(Estimate::with('mail.user', 'customer', 'loa_documents', 'documents', 'activity_log', 'activity_log.user', 'scope', 'scope.sub_services', 'scope.sub_services.sub_services', 'scope.services', 'property', 'property.vehicle', 'insurance')->where('id', $estimate_id)->first());
    }

    public function delete_job_order_loa_document($id, $estimate_id)
    {
        $delete = JobOrderLoaDocument::where('id', $id)->delete();

        return response()->json(JobOrder::with('mail.user', 'loa_documents', 'payables', 'customer', 'timeline.services_type', 'timeline.personnel', 'documents', 'activity_log.user', 'scope', 'scope.sub_services', 'scope.sub_services.sub_services', 'scope.services.services_type', 'property', 'property.vehicle', 'insurance')->where('id', $estimate_id)->first());
    }

}
