<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
}
