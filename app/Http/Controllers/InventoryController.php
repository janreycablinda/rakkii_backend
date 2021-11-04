<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stockout_temp;
use App\Models\Item;
use App\Models\Stockout;


class InventoryController extends Controller
{
    public function find_inventory(Request $request){
        $split_beginning = explode('/', $request->beginning);
        $beginning = $split_beginning[2] . '/' . $split_beginning[0] . '/' . $split_beginning[1] . ' 00:00:00';

        $split_ending = explode('/', $request->ending);
        $ending = $split_ending[2] . '/' . $split_ending[0] . '/' . $split_ending[1] . ' 23:59:59';
        
        $date = array(
            "beginning" => $beginning,
            "ending" => $ending
        );
        
        $get = Item::with(['stockin_temp.stockin' => function ($query) use ($date) {
            $query->whereBetween('date', [$date['beginning'], $date['ending']])->where('is_deleted', false);
        }, 'stockout_temp.stockout' => function ($query) use ($date) {
            $query->whereBetween('purchase_date', [$date['beginning'], $date['ending']])->where('is_deleted', false);
        }, 'group', 'user'])->where('is_deleted', false)->get();

        $stockout_temp = Stockout::with('stockout_temps')->where('delivery_date', '>', $date['ending'])->get();
        
        return response()->json(['items' => $get, 'stockout_temp' => $stockout_temp]);
    }
}
