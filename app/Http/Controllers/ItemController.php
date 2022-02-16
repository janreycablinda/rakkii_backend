<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class ItemController extends Controller
{
    public function items()
    {
        $get = Item::where('is_deleted', false)->get();

        return response()->json($get);
    }

    public function create_item(Request $request)
    {
        $new = new Item;
        $new->product_name = $request->product_name;
        $new->brand = $request->brand;
        $new->user_id = auth()->user()->id;
        $new->save();

        return response()->json($new);
    }
}
