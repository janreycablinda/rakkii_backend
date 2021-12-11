<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\LoaDocument;
use App\Models\Estimate;
use App\Models\EstimateActivityLog;

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

    public function delete_document($id, $estimate_id)
    {
        $delete = Document::where('id', $id)->delete();

        return response()->json(Estimate::with('mail.user', 'customer', 'loa_documents', 'documents', 'activity_log', 'activity_log.user', 'scope', 'scope.sub_services', 'scope.sub_services.sub_services', 'scope.services', 'property', 'property.vehicle', 'insurance')->where('id', $estimate_id)->first());
    }

    public function delete_loa_document($id, $estimate_id)
    {
        $delete = LoaDocument::where('id', $id)->delete();

        return response()->json(Estimate::with('mail.user', 'customer', 'loa_documents', 'documents', 'activity_log', 'activity_log.user', 'scope', 'scope.sub_services', 'scope.sub_services.sub_services', 'scope.services', 'property', 'property.vehicle', 'insurance')->where('id', $estimate_id)->first());
    }

}
