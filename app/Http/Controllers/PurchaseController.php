<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\PurchaseReceipt;
use App\Models\PurchaseItem;
use App\Models\JobOrder;

class PurchaseController extends Controller
{
    public function purchases()
    {
        $get = Purchase::with('receipts', 'purchase_items')->where('is_deleted', false)->get();
    }

    public function add_purchases(Request $request)
    {

        $new = new Purchase;
        $new->job_order_id = $request->id;
        $new->supplier_id = $request->supplier_id;
        $new->date = $request->date;
        $new->user_id = auth()->user()->id;
        $new->save();

        $file_upload = $request->file('files');

        foreach($file_upload as $key => $file){
            $files = 'RP-'. $new->id .time(). $key . '.' . $file->extension();
            $file->move(public_path('img/upload'), $files);

            $file_new = new PurchaseReceipt;
            $file_new->purchase_id = $new->id;
            $file_new->receipt = $files;
            $file_new->save();
        }

        $items = json_decode($request->items, true);

        foreach($items as $key => $item){
            $new_item = new PurchaseItem;
            $new_item->purchase_id = $new->id;
            $new_item->item_id = $item['item_id'];
            $new_item->qty = $item['qty'];
            $new_item->unit_id = $item['unit_id'];
            $new_item->price = $item['price'];
            $new_item->user_id = auth()->user()->id;
            $new_item->save();
        }

        return response()->json(JobOrder::with('mail.user', 'purchases.receipts', 'purchases.purchase_items', 'purchases.supplier', 'loa_documents', 'payables', 'customer', 'timeline.services_type', 'timeline.personnel', 'documents', 'activity_log', 'activity_log.user', 'scope', 'scope.sub_services', 'scope.sub_services.sub_services', 'scope.services.services_type', 'property', 'property.vehicle', 'insurance')->where('id', $request->id)->first());
    }

    public function delete_purchase($id, $job_order_id)
    {
        $delete = Purchase::where('id', $id)->delete();

        return response()->json(JobOrder::with('mail.user', 'purchases.receipts', 'purchases.purchase_items', 'purchases.supplier', 'loa_documents', 'payables', 'customer', 'timeline.services_type', 'timeline.personnel', 'documents', 'activity_log', 'activity_log.user', 'scope', 'scope.sub_services', 'scope.sub_services.sub_services', 'scope.services.services_type', 'property', 'property.vehicle', 'insurance')->where('id', $job_order_id)->first());
    }
}
