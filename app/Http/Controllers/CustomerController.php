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
        $new->website = $request->form['website'];
        $new->state = $request->form['state'];
        $new->zip_code = $request->form['zip_code'];
        $new->country = $request->form['country'];
        $new->user_id = $request->user_id;
        $new->save();

        $newBill = new Billing;
        $newBill->customer_id = $new->id;
        $newBill->street = $request->form_billing['street'];
        $newBill->city = $request->form_billing['city'];
        $newBill->state = $request->form_billing['state'];
        $newBill->zip_code = $request->form_billing['zip_code'];
        $newBill->country = $request->form_billing['country'];
        $newBill->save();

        $newShip = new Shipping;
        $newShip->customer_id = $new->id;
        $newShip->street = $request->form_shipping['street'];
        $newShip->city = $request->form_shipping['city'];
        $newShip->state = $request->form_shipping['state'];
        $newShip->zip_code = $request->form_shipping['zip_code'];
        $newShip->country = $request->form_shipping['country'];
        $newShip->save();

        return response()->json($new->load('billings', 'shippings', 'user'));
    }
}
