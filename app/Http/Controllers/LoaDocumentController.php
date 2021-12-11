<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LoaDocument;

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

        return response()->json(200);
    }
}
