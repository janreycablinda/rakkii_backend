<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unit;

class UnitController extends Controller
{
    public function units()
    {
        $units = Unit::where('is_deleted', false)->get();

        return response()->json($units);
    }
}
