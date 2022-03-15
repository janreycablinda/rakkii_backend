<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function cash_collected_report($period)
    {
        return response()->json($period);
    }

    public function cash_collected_period_report($period, $from, $to)
    {
        
    }

    public function cash_collectables_report($period)
    {

    }

    public function cash_collectables_period_report($period, $from, $to)
    {

    }
}
