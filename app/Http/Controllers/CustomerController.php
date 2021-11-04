<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function customers()
    {
        $get = Customer::where('is_deleted', false)->get();

        return response()->json($get);
    }
    
    public function create_customer(Request $request)
    {
        $new = new Customer;
        $new->company_name = $request->company_name;
        $new->user_id = $request->user_id;
        $new->save();

        return response()->json($new);
    }
}
