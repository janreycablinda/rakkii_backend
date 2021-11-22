<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;

class CountryController extends Controller
{
    public function get_country()
    {
        $get = Country::all();

        return response()->json($get);
    }
}
