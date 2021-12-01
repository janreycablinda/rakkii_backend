<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;

class SupplierController extends Controller
{
    public function supplier()
    {
        $get = Supplier::where('is_deleted', false)->get();

        return response()->json($get);
    }

    public function add_supplier(Request $request)
    {
        $add = new Supplier;
        $add->supplier_name = $request->supplier_name;
        $add->contact_person = $request->contact_person;
        $add->phone = $request->phone;
        $add->email = $request->email;
        $add->address = $request->address;
        $add->tin = $request->tin;
        $add->terms = $request->terms;
        $add->payment_mode = $request->payment_mode;
        $add->check_dated = $request->check_dated;
        $add->shipping_mode = $request->shipping_mode;
        $add->deliver_type = $request->deliver_type;
        $add->deliver_cost = $request->deliver_cost;
        $add->save();

        return response()->json($add);
    }

    public function delete_supplier($id)
    {
        $del = Supplier::where('id', $id)->update([
            'is_deleted' => true
        ]);

        return response()->json(200);
    }
}
