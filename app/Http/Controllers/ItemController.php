<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class ItemController extends Controller
{
    public function items()
    {
        $items = Item::where('is_deleted', false)->get();

        return response()->json($items);
    }

    public function create_item(Request $request)
    {
        $new = new Item();
        $new->item = $request->item;
        $new->description = $request->description;
        $new->brand = $request->brand;
        $new->qty = $request->qty;
        $new->price = $request->price;
        $new->unit = $request->unit;
        $new->notifier = $request->notifier;
        $new->unit_cost = $request->unit_cost;
        $new->group_id = $request->group_id;
        $new->user_id = $request->user_id;
        $new->save();

        return response()->json($new);
    }

    public function update_item(Request $request)
    {
        $update = Item::where('id', $request->id)->update([
            'item' => $request->item,
            'description' => $request->description,
            'brand' => $request->brand,
            'qty' => $request->qty,
            'price' => $request->price,
            'unit' => $request->unit,
            'notifier' => $request->notifier,
            'unit_cost' => $request->unit_cost,
            'group_id' => $request->group_id,
            'user_id' => $request->user_id,
        ]);

        return response()->json($request);
    }

    public function delete_item($id)
    {
        $delete = Item::where('id', $id)->update([
            'is_deleted' => true
        ]);
        
        return response()->json(200);
    }
}
