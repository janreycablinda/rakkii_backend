<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LoaDocument;
use App\Events\RealTimeNotification;
use App\Models\Notification;
use App\Models\Estimate;

class LoaDocumentController extends Controller
{
    public function upload_loa_document(Request $request)
    {
        $file_upload = $request->file('files');
        foreach($file_upload as $key => $file){
            $files = 'LOA-'.time(). $key .'.'. $file->extension();
            $file->move(public_path('img/upload'), $files);

            $new = new LoaDocument;
            $new->estimate_id = $request->id;
            $new->file_name = $files;
            $new->save();
        }

        $get = Estimate::with('insurance')->find($request->id);

        $add = new Notification;
        $add->message = 'New Uploaded Documents from ' . $get->insurance->insurance_name;
        $add->link = '/sales/estimates';
        $add->icon = 'cil-file';
        $add->status = 'unread';
        $add->for = 'all';
        $add->type = 'notification';
        $add->save();

        broadcast(new RealTimeNotification($add));

        return response()->json(200);
    }
}
