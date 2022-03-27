<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BillingStatement;
use App\Models\BillingDetail;
use App\Models\BillingPayment;
use Carbon\Carbon;

class BillingController extends Controller
{
    public function get_billing_statement()
    {
        $get = BillingStatement::with('billing_payment.user', 'billing_details.services', 'billing_details.sub_services', 'customer', 'insurance', 'job_order')->where('is_deleted', false)->get();

        return response()->json($get);
    }

    public function find_billings($id)
    {
        $get = BillingStatement::with('billing_payment.user', 'billing_details.services', 'billing_details.sub_services', 'customer', 'insurance', 'job_order')->find($id);

        return response()->json($get);
    }

    public function get_billing_statement_no()
    {
        return response()->json(BillingStatement::max('billing_statement_no') +1);
    }

    public function submit_payment(Request $request)
    {
        $newpay = new BillingPayment;
        $newpay->billing_statement_id = $request->id;
        $newpay->amount = $request->amount;
        $newpay->payment_type = $request->payment_type;
        $newpay->bank_name = $request->bank_name;
        $newpay->account_name = $request->account_name;
        $newpay->payment_date = $request->payment_date;
        $newpay->check_date = $request->check_date;
        $newpay->check_no = $request->check_no;
        $newpay->user_id = auth()->user()->id;
        $newpay->status = 'Paid';
        $newpay->save();

        return response()->json(['billing' => BillingStatement::with('billing_payment.user', 'billing_details.services', 'billing_details.sub_services', 'customer', 'insurance', 'job_order')->find($request->id), 'new' => $newpay->load('user')]);
    }

    public function create_billing(Request $request)
    {
        $new = new BillingStatement;
        $new->job_order_id = $request->job_order_id;
        if($request->payment_for == 'customer'){
            $new->customer_id = $request->billed_to;
        }else{
            $new->insurance_id = $request->billed_to;
        }
        $new->tin = $request->tin;
        $new->address = $request->address;
        $new->date = $request->date;
        $new->plate_no = $request->plate_no;
        $new->ref_claim_no = $request->ref_claim_no;
        $new->buss_style = $request->buss_style;
        $new->payment_for = $request->payment_for;
        $new->status = 'Unpaid';
        $new->amount = $request->total_amount;
        $new->save();

        if($request->billing_details){
            foreach($request->billing_details as $bill){
                $newbill = new BillingDetail;
                $newbill->billing_statement_id = $new->id;
                if($bill['custom'] == false){
                    $newbill->type = $bill['description']['type'];
                    if($bill['description']['type'] == 'services'){
                        $newbill->services_id = $bill['description']['value'];
                    }else{
                        $newbill->sub_services_id = $bill['description']['value'];
                    }
                }else{
                    $newbill->type = 'custom';
                    $newbill->description = $bill['description'];
                }
                $newbill->unit_cost = $bill['unit_cost'];
                $newbill->qty = $bill['qty'];
                $newbill->labor = $bill['labor'];
                $newbill->materials = $bill['materials'];
                $newbill->custom = $bill['custom'];
                $newbill->save();
            }
        }

        // $newpay = new BillingPayment;
        // $newpay->billing_statement_id = $new->id;
        // $newpay->amount = $request->total_amount;
        // $newpay->user_id = auth()->user()->id;
        // $newpay->status = 'Unpaid';
        // $newpay->save();

        return response()->json($new);
    }

    public function delete_billing_statement($id)
    {
        $del = BillingStatement::find($id)->delete();

        $update = BillingPayment::where('billing_statement_id', $id)->update([
            'is_deleted' => true
        ]);

        return response()->json(200);
    }
}
