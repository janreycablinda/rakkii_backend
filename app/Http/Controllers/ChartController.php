<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;

class ChartController extends Controller
{
    public function cash_collected()
    {
        $get = DB::table("billing_payments")->select(DB::raw('sum(amount) as `amount`'), DB::raw('YEAR(payment_date) year, MONTH(payment_date) month'))
        ->groupby('year','month')
        ->where('is_deleted', false)
        ->get();

        return response()->json($get);
    }

    public function cash_collectables()
    {
        $get = DB::table("billing_statements")->select(DB::raw('sum(amount) as `total_payables`'), DB::raw('YEAR(date) year, MONTH(date) month'))
        ->groupby('year','month')
        ->get();

        return response()->json($get);
    }
}
