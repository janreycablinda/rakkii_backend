<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobOrder;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function cash_collected_report($period)
    {
        $get = '';
        return response()->json($period);
    }

    public function generate_report(Request $request)
    {
        if($request->period == 'This Month'){
            if($request->status == 'Completed'){
                $get = JobOrder::with('agent', 'other_expenses', 'billings.billing_payment', 'billings.insurance', 'billings.job_order', 'billings.customer', 'gatepass.user', 'payments.user', 'mail.user', 'purchases.receipts', 'purchases.purchase_items', 'purchases.supplier', 'loa_documents', 'payables', 'customer', 'timeline.services_type', 'timeline.personnel', 'documents', 'activity_log.user', 'scope', 'scope.sub_services', 'scope.sub_services.sub_services', 'scope.services.services_type', 'property', 'property.vehicle', 'insurance')->whereBetween('date', [Carbon::now()->firstOfMonth(), Carbon::now()->endOfMonth()])->where('status', 'completed')->where('is_deleted', false)->get();
            }else{
                $get = JobOrder::with('agent', 'other_expenses','billings.billing_payment', 'billings.insurance', 'billings.job_order', 'billings.customer', 'gatepass.user', 'payments.user', 'mail.user', 'purchases.receipts', 'purchases.purchase_items', 'purchases.supplier', 'loa_documents', 'payables', 'customer', 'timeline.services_type', 'timeline.personnel', 'documents', 'activity_log.user', 'scope', 'scope.sub_services', 'scope.sub_services.sub_services', 'scope.services.services_type', 'property', 'property.vehicle', 'insurance')->whereBetween('date', [Carbon::now()->firstOfMonth(), Carbon::now()->endOfMonth()])->where('is_deleted', false)->get();
            }
        }else if($request->period == 'Last Month'){
            if($request->status == 'Completed'){
                $get = JobOrder::with('agent','other_expenses','billings.billing_payment', 'billings.insurance', 'billings.job_order', 'billings.customer', 'gatepass.user', 'payments.user', 'mail.user', 'purchases.receipts', 'purchases.purchase_items', 'purchases.supplier', 'loa_documents', 'payables', 'customer', 'timeline.services_type', 'timeline.personnel', 'documents', 'activity_log.user', 'scope', 'scope.sub_services', 'scope.sub_services.sub_services', 'scope.services.services_type', 'property', 'property.vehicle', 'insurance')->whereBetween('date', [Carbon::now()->subMonth()->firstOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->where('status', 'completed')->where('is_deleted', false)->get();
            }else{
                $get = JobOrder::with('agent','other_expenses','billings.billing_payment', 'billings.insurance', 'billings.job_order', 'billings.customer', 'gatepass.user', 'payments.user', 'mail.user', 'purchases.receipts', 'purchases.purchase_items', 'purchases.supplier', 'loa_documents', 'payables', 'customer', 'timeline.services_type', 'timeline.personnel', 'documents', 'activity_log.user', 'scope', 'scope.sub_services', 'scope.sub_services.sub_services', 'scope.services.services_type', 'property', 'property.vehicle', 'insurance')->whereBetween('date', [Carbon::now()->subMonth()->firstOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->where('is_deleted', false)->get();
            }
        }else if($request->period == 'This Year'){
            if($request->status == 'Completed'){
                $get = JobOrder::with('agent','other_expenses','billings.billing_payment', 'billings.insurance', 'billings.job_order', 'billings.customer', 'gatepass.user', 'payments.user', 'mail.user', 'purchases.receipts', 'purchases.purchase_items', 'purchases.supplier', 'loa_documents', 'payables', 'customer', 'timeline.services_type', 'timeline.personnel', 'documents', 'activity_log.user', 'scope', 'scope.sub_services', 'scope.sub_services.sub_services', 'scope.services.services_type', 'property', 'property.vehicle', 'insurance')->whereBetween('date', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()])->where('status', 'completed')->where('is_deleted', false)->get();
            }else{
                $get = JobOrder::with('agent','other_expenses','billings.billing_payment', 'billings.insurance', 'billings.job_order', 'billings.customer', 'gatepass.user', 'payments.user', 'mail.user', 'purchases.receipts', 'purchases.purchase_items', 'purchases.supplier', 'loa_documents', 'payables', 'customer', 'timeline.services_type', 'timeline.personnel', 'documents', 'activity_log.user', 'scope', 'scope.sub_services', 'scope.sub_services.sub_services', 'scope.services.services_type', 'property', 'property.vehicle', 'insurance')->whereBetween('date', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()])->where('is_deleted', false)->get();
            }
        }else if($request->period == 'Last Year'){
            if($request->status == 'Completed'){
                $get = JobOrder::with('agent','other_expenses','billings.billing_payment', 'billings.insurance', 'billings.job_order', 'billings.customer', 'gatepass.user', 'payments.user', 'mail.user', 'purchases.receipts', 'purchases.purchase_items', 'purchases.supplier', 'loa_documents', 'payables', 'customer', 'timeline.services_type', 'timeline.personnel', 'documents', 'activity_log.user', 'scope', 'scope.sub_services', 'scope.sub_services.sub_services', 'scope.services.services_type', 'property', 'property.vehicle', 'insurance')->whereBetween('date', [Carbon::now()->subYear()->startOfYear(), Carbon::now()->subYear()->endOfYear()])->where('status', 'completed')->where('is_deleted', false)->get();
            }else{
                $get = JobOrder::with('agent','other_expenses','billings.billing_payment', 'billings.insurance', 'billings.job_order', 'billings.customer', 'gatepass.user', 'payments.user', 'mail.user', 'purchases.receipts', 'purchases.purchase_items', 'purchases.supplier', 'loa_documents', 'payables', 'customer', 'timeline.services_type', 'timeline.personnel', 'documents', 'activity_log.user', 'scope', 'scope.sub_services', 'scope.sub_services.sub_services', 'scope.services.services_type', 'property', 'property.vehicle', 'insurance')->whereBetween('date', [Carbon::now()->subYear()->startOfYear(), Carbon::now()->subYear()->endOfYear()])->where('is_deleted', false)->get();
            }
        }else if($request->period == 'Last 3 months'){
            if($request->status == 'Completed'){
                $get = JobOrder::with('agent','other_expenses','billings.billing_payment', 'billings.insurance', 'billings.job_order', 'billings.customer', 'gatepass.user', 'payments.user', 'mail.user', 'purchases.receipts', 'purchases.purchase_items', 'purchases.supplier', 'loa_documents', 'payables', 'customer', 'timeline.services_type', 'timeline.personnel', 'documents', 'activity_log.user', 'scope', 'scope.sub_services', 'scope.sub_services.sub_services', 'scope.services.services_type', 'property', 'property.vehicle', 'insurance')->whereBetween('date', [Carbon::now()->subMonths(3)->firstOfMonth(), Carbon::now()->endOfMonth()])->where('status', 'completed')->where('is_deleted', false)->get();
            }else{
                $get = JobOrder::with('agent','other_expenses','billings.billing_payment', 'billings.insurance', 'billings.job_order', 'billings.customer', 'gatepass.user', 'payments.user', 'mail.user', 'purchases.receipts', 'purchases.purchase_items', 'purchases.supplier', 'loa_documents', 'payables', 'customer', 'timeline.services_type', 'timeline.personnel', 'documents', 'activity_log.user', 'scope', 'scope.sub_services', 'scope.sub_services.sub_services', 'scope.services.services_type', 'property', 'property.vehicle', 'insurance')->whereBetween('date', [Carbon::now()->subMonths(3)->firstOfMonth(), Carbon::now()->endOfMonth()])->where('is_deleted', false)->get();
            }
        }else if($request->period == 'Last 6 months'){
            if($request->status == 'Completed'){
                $get = JobOrder::with('agent','other_expenses','billings.billing_payment', 'billings.insurance', 'billings.job_order', 'billings.customer', 'gatepass.user', 'payments.user', 'mail.user', 'purchases.receipts', 'purchases.purchase_items', 'purchases.supplier', 'loa_documents', 'payables', 'customer', 'timeline.services_type', 'timeline.personnel', 'documents', 'activity_log.user', 'scope', 'scope.sub_services', 'scope.sub_services.sub_services', 'scope.services.services_type', 'property', 'property.vehicle', 'insurance')->whereBetween('date', [Carbon::now()->subMonths(6)->firstOfMonth(), Carbon::now()->endOfMonth()])->where('status', 'completed')->where('is_deleted', false)->get();
            }else{
                $get = JobOrder::with('agent','other_expenses','billings.billing_payment', 'billings.insurance', 'billings.job_order', 'billings.customer', 'gatepass.user', 'payments.user', 'mail.user', 'purchases.receipts', 'purchases.purchase_items', 'purchases.supplier', 'loa_documents', 'payables', 'customer', 'timeline.services_type', 'timeline.personnel', 'documents', 'activity_log.user', 'scope', 'scope.sub_services', 'scope.sub_services.sub_services', 'scope.services.services_type', 'property', 'property.vehicle', 'insurance')->whereBetween('date', [Carbon::now()->subMonths(6)->firstOfMonth(), Carbon::now()->endOfMonth()])->where('is_deleted', false)->get();
            }
        }else if($request->period == 'Last 12 months'){
            if($request->status == 'Completed'){
                $get = JobOrder::with('agent','other_expenses','billings.billing_payment', 'billings.insurance', 'billings.job_order', 'billings.customer', 'gatepass.user', 'payments.user', 'mail.user', 'purchases.receipts', 'purchases.purchase_items', 'purchases.supplier', 'loa_documents', 'payables', 'customer', 'timeline.services_type', 'timeline.personnel', 'documents', 'activity_log.user', 'scope', 'scope.sub_services', 'scope.sub_services.sub_services', 'scope.services.services_type', 'property', 'property.vehicle', 'insurance')->whereBetween('date', [Carbon::now()->subMonths(12)->firstOfMonth(), Carbon::now()->endOfMonth()])->where('status', 'completed')->where('is_deleted', false)->get();
            }else{
                $get = JobOrder::with('agent','other_expenses','billings.billing_payment', 'billings.insurance', 'billings.job_order', 'billings.customer', 'gatepass.user', 'payments.user', 'mail.user', 'purchases.receipts', 'purchases.purchase_items', 'purchases.supplier', 'loa_documents', 'payables', 'customer', 'timeline.services_type', 'timeline.personnel', 'documents', 'activity_log.user', 'scope', 'scope.sub_services', 'scope.sub_services.sub_services', 'scope.services.services_type', 'property', 'property.vehicle', 'insurance')->whereBetween('date', [Carbon::now()->subMonths(12)->firstOfMonth(), Carbon::now()->endOfMonth()])->where('is_deleted', false)->get();
            }
        }else if($request->period == 'Period'){
            $from = $request->from . ' 00:00:01';
            $to = $request->to . ' 23:59:00';
            if($request->status == 'Completed'){
                $get = JobOrder::with('agent','other_expenses','billings.billing_payment', 'billings.insurance', 'billings.job_order', 'billings.customer', 'gatepass.user', 'payments.user', 'mail.user', 'purchases.receipts', 'purchases.purchase_items', 'purchases.supplier', 'loa_documents', 'payables', 'customer', 'timeline.services_type', 'timeline.personnel', 'documents', 'activity_log.user', 'scope', 'scope.sub_services', 'scope.sub_services.sub_services', 'scope.services.services_type', 'property', 'property.vehicle', 'insurance')->whereBetween('date', [$from, $to])->where('status', 'completed')->where('is_deleted', false)->get();
            }else{
                $get = JobOrder::with('agent','other_expenses','billings.billing_payment', 'billings.insurance', 'billings.job_order', 'billings.customer', 'gatepass.user', 'payments.user', 'mail.user', 'purchases.receipts', 'purchases.purchase_items', 'purchases.supplier', 'loa_documents', 'payables', 'customer', 'timeline.services_type', 'timeline.personnel', 'documents', 'activity_log.user', 'scope', 'scope.sub_services', 'scope.sub_services.sub_services', 'scope.services.services_type', 'property', 'property.vehicle', 'insurance')->whereBetween('date', [$from, $to])->where('is_deleted', false)->get();
            }
        }else{
            if($request->status == 'Completed'){
                $get = JobOrder::with('agent','other_expenses','billings.billing_payment', 'billings.insurance', 'billings.job_order', 'billings.customer', 'gatepass.user', 'payments.user', 'mail.user', 'purchases.receipts', 'purchases.purchase_items', 'purchases.supplier', 'loa_documents', 'payables', 'customer', 'timeline.services_type', 'timeline.personnel', 'documents', 'activity_log.user', 'scope', 'scope.sub_services', 'scope.sub_services.sub_services', 'scope.services.services_type', 'property', 'property.vehicle', 'insurance')->where('status', 'completed')->where('is_deleted', false)->get();
            }else{
                $get = JobOrder::with('agent','other_expenses','billings.billing_payment', 'billings.insurance', 'billings.job_order', 'billings.customer', 'gatepass.user', 'payments.user', 'mail.user', 'purchases.receipts', 'purchases.purchase_items', 'purchases.supplier', 'loa_documents', 'payables', 'customer', 'timeline.services_type', 'timeline.personnel', 'documents', 'activity_log.user', 'scope', 'scope.sub_services', 'scope.sub_services.sub_services', 'scope.services.services_type', 'property', 'property.vehicle', 'insurance')->where('is_deleted', false)->get();
            }
        }
        
        return response()->json($get);
    }

    public function cash_collected_period_report($period, $from, $to)
    {

    }

    public function cash_collectables_report($period)
    {

    }

    public function cash_collectables_period_report($period, $from, $to)
    {

    }
}
