<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\BillingPayment;
use App\Models\JobOrder;
use Carbon\Carbon;
use App\Models\BillingStatement;
use DB;

class PaymentController extends Controller
{
    public function payments(){
        $get = BillingPayment::whereDate('payment_date', Carbon::today())->get();

        return response()->json($get);
    }

    public function weekly_payment(){
        // $get = Payment::whereBetween('date', [Carbon::now()->subDays(7)->startOfWeek(), Carbon::now()->endOfWeek()])->get();
        $get = DB::table("billing_payments")->select(DB::raw('sum(amount) as `data`'), DB::raw('DAY(payment_date) day, MONTH(payment_date) month, YEAR(payment_date) year'))
        ->groupby('day', 'month', 'year')
        ->whereBetween('payment_date', [Carbon::now()->subDays(7)->startOfWeek(), Carbon::now()->endOfWeek()])
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

    public function update_payment(Request $request)
    {
        $update = BillingPayment::where('id', $request->id)->update([
            'amount' => $request->amount,
            'payment_type' => $request->payment_type,
            'bank_name' => $request->bank_name,
            'account_name' => $request->account_name,
            'payment_date' => $request->payment_date,
            'check_date' => $request->check_date,
            'check_no' => $request->check_no,
            'user_id' => auth()->user()->id,
            'amount' => $request->amount
        ]);
        
        return response()->json(['billing' => BillingStatement::with('billing_payment.user', 'billing_details.services', 'billing_details.sub_services', 'customer', 'insurance', 'job_order')->find($request->billing_statement_id), 'updated' => BillingPayment::with('user')->find($request->id)]);
    }

    public function delete_payment($id, $billing_statement_id){
        
        $del = BillingPayment::where('id', $id)->delete();

        return response()->json(BillingStatement::with('billing_payment.user', 'billing_details.services', 'billing_details.sub_services', 'customer', 'insurance', 'job_order')->find($billing_statement_id));
    }

    public function payment_list()
    {
        $get = Payment::with('job_order.customer', 'user')->orderBy('id', 'DESC')->get();

        return response()->json($get);
    }

    public function delete_payment_list($id){
        $del = BillingPayment::find($id)->delete();

        return response()->json(200);
    }
}
