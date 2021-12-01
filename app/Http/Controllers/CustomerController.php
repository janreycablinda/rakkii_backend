<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Billing;
use App\Models\Shipping;

class CustomerController extends Controller
{
    public function customers()
    {
        $get = Customer::where('is_deleted', false)->get();

        return response()->json($get);
    }

    public function get_customer_profile($id)
    {
        $profile = Customer::find($id);

        return $profile;
    }
    
    public function create_customer(Request $request)
    {
        $new = new Customer;
        $new->company_name = $request->form['company_name'];
        $new->address = $request->form['address'];
        $new->phone = $request->form['phone'];
        $new->user_id = $request->user_id;
        $new->save();

        return response()->json($new->load('user'));
    }

    public function delete_customer($id)
    {
        $del = Customer::where('id', $id)->update([
            'is_deleted' => true
        ]);

        return response()->json(200);
    }
}
