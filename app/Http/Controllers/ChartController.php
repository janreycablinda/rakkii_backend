<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;

class ChartController extends Controller
{
    public function cash_collected()
    {
        $get = DB::table("payments")->select(DB::raw('sum(amount) as `amount`'), DB::raw('YEAR(date) year, MONTH(date) month'))
        ->groupby('year','month')
        ->where('status', 'paid')
        ->get();

        return response()->json($get);
    }

    public function cash_collectables()
    {
        $get = DB::table("job_orders as j")->join('payables as p', 'j.id', '=', 'p.job_order_id')->select(DB::raw('sum(p.total_repair_cost) as `total_payables`'), DB::raw('sum(p.discount) as `discount`'), DB::raw('YEAR(j.date) year, MONTH(j.date) month'))
        ->groupby('year','month')
        ->get();

        return response()->json($get);
    }
}
