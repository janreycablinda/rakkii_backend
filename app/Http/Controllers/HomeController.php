<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;

class HomeController extends Controller
{
    public function test()
    {

        return view('test');
    }
}
