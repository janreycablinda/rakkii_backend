<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\JobOrder;
use Carbon\Carbon;
use DB;

class PaymentController extends Controller
{
    public function payments(){
        $get = Payment::whereDate('date', Carbon::today())->get();

        return response()->json($get);
    }

    public function weekly_payment(){
        // $get = Payment::whereBetween('date', [Carbon::now()->subDays(7)->startOfWeek(), Carbon::now()->endOfWeek()])->get();
        $get = DB::table("payments")->select(DB::raw('sum(amount) as `data`'), DB::raw('DAY(date) day, MONTH(date) month, YEAR(date) year'))
        ->groupby('day', 'month', 'year')
        ->whereBetween('date', [Carbon::now()->subDays(7)->startOfWeek(), Carbon::now()->endOfWeek()])
        ->get();
        
        return response()->json(['start_of_week' => Carbon::now()->subDays(7)->startOfWeek(), 'middle' => Carbon::now()->subDays(7)->startOfWeek()->addDays(6)->endOfDay(), 'end_of_week' => Carbon::now()->endOfWeek(), 'data' => $get]);
    }

    public function add_payment(Request $request){
        $new = new Payment;
        $new->job_order_id = $request->job_order_id;
        $new->date = $request->date;
        $new->payment_of = $request->payment_of;
        $new->amount = $request->amount;
        $new->status = 'paid';
        $new->user_id = auth()->user()->id;
        $new->save();

        return response()->json(['payments' => $new->load('user'), 'job_order' => JobOrder::with('gatepass.user', 'payments.user', 'mail.user', 'purchases.receipts', 'purchases.purchase_items', 'purchases.supplier', 'loa_documents', 'payables', 'customer', 'timeline.services_type', 'timeline.personnel', 'documents', 'activity_log', 'activity_log.user', 'scope', 'scope.sub_services', 'scope.sub_services.sub_services', 'scope.services.services_type', 'property', 'property.vehicle', 'insurance')->find($request->job_order_id)]);
    }

    public function delete_payment($id, $job_order_id){
        $del = Payment::query()
        ->where('id', $id)
        ->each(function ($oldRecord) {
            $newRecord = $oldRecord->replicate();
            $newRecord->setTable('deleted_payments');
            $newRecord->save();
            $oldRecord->delete();
        });
        // $del = Payment::where('id', $id)->delete();

        return response()->json(JobOrder::with('gatepass.user', 'payments.user', 'mail.user', 'purchases.receipts', 'purchases.purchase_items', 'purchases.supplier', 'loa_documents', 'payables', 'customer', 'timeline.services_type', 'timeline.personnel', 'documents', 'activity_log', 'activity_log.user', 'scope', 'scope.sub_services', 'scope.sub_services.sub_services', 'scope.services.services_type', 'property', 'property.vehicle', 'insurance')->find($job_order_id));
    }
}
