<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExpensesType;

class ExpensesTypeController extends Controller
{
    public function expenses_type()
    {
        $get = ExpensesType::where('is_deleted', false)->get();

        return response()->json($get);
    }

    public function create_expenses_type(Request $request)
    {
        $new = new ExpensesType;
        $new->expenses_name = $request->expenses_name;
        $new->save();

        return response()->json($new);
    }

    public function delete_expenses_type($id)
    {
        $del = ExpensesType::where('id', $id)->update([
            'is_deleted' => true
        ]);

        return response()->json(200);
    }
}
