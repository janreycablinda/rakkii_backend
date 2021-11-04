<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stockin;
use App\Models\Stockin_temp;
use App\Models\Item;
use Carbon\Carbon;

class StockinController extends Controller
{
    public function stockin(Request $request)
    {
        $get = Stockin::with('user', 'stockin_temps.item')->where('is_deleted', false)->get();

        return response()->json($get);
    }

    public function create_stockin(Request $request)
    {

        if($request->image){
            $imageName = 'RCPT-'.time().'.'. $request->image->getClientOriginalExtension();
            $request->image->move(public_path('img/upload'), $imageName);
        }else{
            $imageName = null;
        }
        
        $addstock = new Stockin();
        $addstock->total_cost = $request->total_cost;
        $addstock->date = Carbon::parse($request->date)->format('Y-m-d H:m:s');
        $addstock->receipt = $imageName;
        $addstock->user_id = $request->user_id;
        $addstock->save();

        $stockin_temp = json_decode($request->stockin_temp, true);
        
        $items = [];
        foreach($stockin_temp as $temp){
            $new = new Stockin_temp;
            $new->stockin_id = $addstock->id;
            $new->item_id = $temp['item_id'];
            $new->qty = $temp['qty'];
            $new->save();

            $increment = Item::where('id', $temp['item_id'])->increment('qty', $temp['qty']);
            $item = Item::with('user', 'group')->where('id', $temp['item_id'])->first();

            array_push($items, $item);
        }

        return response()->json(['stockin' => $addstock->load('user', 'stockin_temps.item'), 'item' => $items]);
    }

    public function update_stockin(Request $request) 
    {
        
        if($request->image){
            $imageName = 'RCPT-'.time().'.'. $request->image->getClientOriginalExtension();
            $request->image->move(public_path('img/upload'), $imageName);
        }else{
            $imageName = null;
        }

        $update = Stockin::where('id', $request->id)->update([
            'total_cost' => $request->total_cost,
            'date' => Carbon::parse($request->date)->format('Y-m-d H:m:s'),
            'receipt' => $imageName,
            'user_id' => $request->user_id
        ]);

        $delete_stockin_temp = Stockin_temp::where('stockin_id', $request->id)->get();
        foreach($delete_stockin_temp as $delete_temp){
            $decrement = Item::where('id', $delete_temp['item_id'])->decrement('qty', $delete_temp['qty']);
            $delete = Stockin_temp::where('stockin_id', $request->id)->where('item_id', $delete_temp['item_id'])->delete();
        }
        
        $stockin_temp = json_decode($request->stockin_temp, true);

        $items = [];
        foreach($stockin_temp as $temp){
            $new = new Stockin_temp;
            $new->stockin_id = $request->id;
            $new->item_id = $temp['item_id'];
            $new->qty = $temp['qty'];
            $new->save();

            $increment = Item::where('id', $temp['item_id'])->increment('qty', $temp['qty']);
            $item = Item::with('user', 'group')->where('id', $temp['item_id'])->first();

            array_push($items, $item);
        }

        return response()->json(['stockin' => Stockin::with('user', 'stockin_temps.item')->where('id', $request->id)->first(), 'item' => $items]);
    }

    public function delete_stockin($id)
    {
        $delete = Stockin::where('id', $id)->update([
            'is_deleted' => true
        ]);

        $get_stocktemp = Stockin_temp::where('stockin_id', $id)->get();

        $items = [];
        foreach($get_stocktemp as $temp){
            $decrement = Item::where('id', $temp['item_id'])->decrement('qty', $temp['qty']);

            $item = Item::with('user', 'group')->where('id', $temp['item_id'])->first();

            array_push($items, $item);
        }

        return response()->json(['items' => $items, 'status' => 200]);
    }
}
