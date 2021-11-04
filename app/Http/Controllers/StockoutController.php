<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stockout;
use App\Models\Stockout_temp;
use App\Models\Item;
use Carbon\Carbon;
use App\Models\Customer;
use App\Models\Notification;

class StockoutController extends Controller
{
    public function stockout()
    {
        $get = Stockout::with('stockout_temps.item', 'customer', 'user')->where('is_deleted', false)->get();

        return response()->json($get);
    }

    public function create_stockout(Request $request)
    {
        
        if($request->custom){
            $newcustomer = new Customer;
            $newcustomer->company_name = $request->company_name;
            $newcustomer->user_id = $request->user_id;
            $newcustomer->is_deleted = true;
            $newcustomer->save();

            $new = new Stockout;
            $new->customer_id = $newcustomer->id;
            $new->purchase_date =Carbon::parse($request->purchase_date)->format('Y-m-d H:m:s');
            $new->delivery_date = Carbon::parse($request->delivery_date)->format('Y-m-d H:m:s');
            $new->user_id = $request->user_id;
            $new->save();
        }else{
            $new = new Stockout;
            $new->customer_id = $request->customer_id;
            $new->purchase_date =Carbon::parse($request->purchase_date)->format('Y-m-d H:m:s');
            $new->delivery_date = Carbon::parse($request->delivery_date)->format('Y-m-d H:m:s');
            $new->user_id = $request->user_id;
            $new->save();
        }
        
        $items = [];
        foreach($request->stockout_temp as $temp){
            $addtemp = new Stockout_temp;
            $addtemp->stockout_id = $new->id;
            $addtemp->item_id = $temp['item_id'];
            $addtemp->qty = $temp['qty'];
            $addtemp->save();

            $decrement = Item::where('id', $temp['item_id'])->decrement('qty', $temp['qty']);
            $getitem = Item::with('group')->where('id', $temp['item_id'])->first();

            if($getitem->qty <= $getitem->notifier){
                $notify = new Notification;
                $notify->message = $getitem->brand . '-' . $getitem->particular . ' has low stock!';
                $notify->link = 'http://localhost:8080/transaction/inventory/items';
                $notify->icon = 'cil-log';
                $notify->status = 'unread';
                $notify->for = 'branch';
                $notify->type = 'stock';
                $notify->user_id = $request->user_id;
                $notify->save();

                $notification = $notify;
            }else{
                $notification = [];
            }
            array_push($items, $getitem);
        }

        return response()->json(['stockout' => $new->load('stockout_temps', 'customer', 'user'), 'item' => $items, 'notification' => $notification]);
    }

    public function update_stockout(Request $request) {
        
        if($request->custom){
            $newcustomer = new Customer;
            $newcustomer->company_name = $request->company_name;
            $newcustomer->user_id = $request->user_id;
            $newcustomer->is_deleted = true;
            $newcustomer->save();

            $update = Stockout::where('id', $request->id)->update([
                'customer_id' => $newcustomer->id,
                'purchase_date' => Carbon::parse($request->purchase_date)->format('Y-m-d H:m:s'),
                'delivery_date' => Carbon::parse($request->delivery_date)->format('Y-m-d H:m:s'),
                'user_id' => $request->user_id
            ]);
        }else{

            $update = Stockout::where('id', $request->id)->update([
                'customer_id' => $request->customer_id,
                'purchase_date' => Carbon::parse($request->purchase_date)->format('Y-m-d H:m:s'),
                'delivery_date' => Carbon::parse($request->delivery_date)->format('Y-m-d H:m:s'),
                'user_id' => $request->user_id
            ]);
        }

        $delete_stockout_temp = Stockout_temp::where('stockout_id', $request->id)->get();
        foreach($delete_stockout_temp as $delete_temp){
            $increment = Item::where('id', $delete_temp['item_id'])->increment('qty', $delete_temp['qty']);
            $delete = Stockout_temp::where('stockout_id', $request->id)->where('item_id', $delete_temp['item_id'])->delete();
        }

        $items = [];
        foreach($request->stockout_temp as $temp){

            $addtemp = new Stockout_temp;
            $addtemp->stockout_id = $request->id;
            $addtemp->item_id = $temp['item_id'];
            $addtemp->qty = $temp['qty'];
            $addtemp->save();

            $decrement = Item::where('id', $temp['item_id'])->decrement('qty', $temp['qty']);
            $getitem = Item::with('group')->where('id', $temp['item_id'])->first();

            if($getitem->qty <= $getitem->notifier){
                $notify = new Notification;
                $notify->message = $getitem->brand . '-' . $getitem->particular . ' has low stock!';
                $notify->link = 'http://localhost:8080/transaction/inventory/items';
                $notify->icon = 'cil-log';
                $notify->status = 'unread';
                $notify->for = 'branch';
                $notify->type = 'stock';
                $notify->user_id = $request->user_id;
                $notify->save();

                $notification = $notify;
            }else{
                $notification = [];
            }
            array_push($items, $getitem);
        }

        return response()->json(['stockout' => Stockout::with('stockout_temps.item', 'customer', 'user')->where('id', $request->id)->first(), 'item' => $items, 'notification' => $notification]);
    }

    public function check_item_availability(Request $request)
    {
        $find = Item::find($request->item_id);
        if($find->qty < $request->qty){
            return response()->json(500);
        }else{
            return response()->json(200);
        }
    }

    public function delete_stockout($id)
    {
        $delete = Stockout::where('id', $id)->update([
            'is_deleted' => true
        ]);

        $get_stocktemp = Stockout_temp::where('stockout_id', $id)->get();

        $items = [];
        foreach($get_stocktemp as $temp){
            $increment = Item::where('id', $temp['item_id'])->increment('qty', $temp['qty']);

            $item = Item::with('user', 'group')->where('id', $temp['item_id'])->first();

            array_push($items, $item);
        }

        return response()->json(['items' => $items, 'status' => 200]);
    }
}
