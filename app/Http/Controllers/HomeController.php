<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;

class HomeController extends Controller
{
    public function test()
    {
        $filename = 'img/upload/CIP-1637383815.pdf';
        $path = public_path($filename);
        $headers = ['Content-Type' => 'application/pdf'];

        // return response()->file($path, $headers);
        return Response::make(file_get_contents($path), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$filename.'"'
        ]);

        // return view('test');
    }
}
