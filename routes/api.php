<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', 'App\Http\Controllers\AuthController@login');
    Route::post('logout', 'App\Http\Controllers\AuthController@logout');
    Route::post('refresh', 'App\Http\Controllers\AuthController@refresh');
    Route::get('me', 'App\Http\Controllers\AuthController@me');

});

Route::group([

    'middleware' => 'api',
    'prefix' => 'resources'

], function ($router) {
    Route::get('users', 'App\Http\Controllers\UserController@users');
    Route::post('add_user', 'App\Http\Controllers\UserController@add_user');
    Route::put('update_user', 'App\Http\Controllers\UserController@update_user');
    Route::delete('delete_user/{id}', 'App\Http\Controllers\UserController@delete_user');

    Route::get('units', 'App\Http\Controllers\UnitController@units');
    Route::post('add_unit', 'App\Http\Controllers\UnitController@add_unit');
    Route::delete('delete_unit/{id}', 'App\Http\Controllers\UnitController@delete_unit');
    Route::get('get_country', 'App\Http\Controllers\CountryController@get_country');

    Route::get('roles', 'App\Http\Controllers\RoleController@roles');
    Route::get('role_options', 'App\Http\Controllers\RoleController@role_options');
    Route::post('add_role', 'App\Http\Controllers\RoleController@add_role');
    Route::put('update_role', 'App\Http\Controllers\RoleController@update_role');
    Route::delete('delete_role/{id}', 'App\Http\Controllers\RoleController@delete_role');

    Route::get('groups', 'App\Http\Controllers\GroupController@groups');
    Route::post('create_group', 'App\Http\Controllers\GroupController@create_group');
    Route::put('update_group', 'App\Http\Controllers\GroupController@update_group');
    Route::delete('delete_group/{id}', 'App\Http\Controllers\GroupController@delete_group');
    
    Route::get('items', 'App\Http\Controllers\ItemController@items');
    Route::post('create_item', 'App\Http\Controllers\ItemController@create_item');
    // Route::put('update_item', 'App\Http\Controllers\ItemController@update_item');
    Route::delete('delete_item/{id}', 'App\Http\Controllers\ItemController@delete_item');
    
    Route::get('job_orders', 'App\Http\Controllers\JobOrderController@job_orders');
    Route::post('start_working', 'App\Http\Controllers\JobOrderController@start_working');
    Route::post('start_working_timeline', 'App\Http\Controllers\JobOrderController@start_working_timeline');
    Route::post('complete_timeline', 'App\Http\Controllers\JobOrderController@complete_timeline');
    Route::get('find_timeline/{id}', 'App\Http\Controllers\JobOrderController@find_timeline');
    Route::post('update_timeline', 'App\Http\Controllers\JobOrderController@update_timeline');
    Route::get('find_job_order/{id}', 'App\Http\Controllers\JobOrderController@find_job_order');
    Route::get('find_customer_job_order/{id}', 'App\Http\Controllers\JobOrderController@find_customer_job_order');
    Route::delete('delete_job_order/{id}', 'App\Http\Controllers\JobOrderController@delete_job_order');
    
    Route::get('find_job_order/{id}/{property_id}/{status}', 'App\Http\Controllers\JobOrderController@find_job_order_status');
    Route::post('update_job_order', 'App\Http\Controllers\JobOrderController@update_job_order');
    Route::post('send_job_order_estimate_to_loa', 'App\Http\Controllers\JobOrderController@send_job_order_estimate_to_loa');
    // Route::post('add_purchase', 'App\Http\Controllers\JobOrderController@add_purchase');
    Route::post('job_order_complete', 'App\Http\Controllers\JobOrderController@job_order_complete');

    Route::post('update_status_job_order', 'App\Http\Controllers\JobOrderController@update_status_job_order');

    Route::post('import_item', 'App\Http\Controllers\ImportController@import_item');
    
    Route::get('stockin', 'App\Http\Controllers\StockinController@stockin');
    Route::post('create_stockin', 'App\Http\Controllers\StockinController@create_stockin');
    Route::post('update_stockin', 'App\Http\Controllers\StockinController@update_stockin');
    Route::delete('delete_stockin/{id}', 'App\Http\Controllers\StockinController@delete_stockin');

    Route::get('stockout', 'App\Http\Controllers\StockoutController@stockout');
    Route::post('check_item_availability', 'App\Http\Controllers\StockoutController@check_item_availability');
    Route::post('create_stockout', 'App\Http\Controllers\StockoutController@create_stockout');
    Route::put('update_stockout', 'App\Http\Controllers\StockoutController@update_stockout');
    Route::delete('delete_stockout/{id}', 'App\Http\Controllers\StockoutController@delete_stockout');

    Route::get('customers', 'App\Http\Controllers\CustomerController@customers');
    Route::post('create_customer', 'App\Http\Controllers\CustomerController@create_customer');
    Route::put('update_customer', 'App\Http\Controllers\CustomerController@update_customer');
    Route::delete('delete_customer/{id}', 'App\Http\Controllers\CustomerController@delete_customer');

    Route::get('find_inventory/{date}', 'App\Http\Controllers\InventoryController@find_inventory');

    Route::get('services_type', 'App\Http\Controllers\ServicesTypeController@services_type');
    Route::post('add_services_type', 'App\Http\Controllers\ServicesTypeController@add_services_type');
    Route::delete('delete_services_type/{id}', 'App\Http\Controllers\ServicesTypeController@delete_services_type');

    Route::get('services', 'App\Http\Controllers\ServicesController@services');
    Route::post('add_services', 'App\Http\Controllers\ServicesController@add_services');
    Route::get('find_services/{id}', 'App\Http\Controllers\ServicesController@find_services');
    Route::delete('delete_services/{id}', 'App\Http\Controllers\ServicesController@delete_services');

    Route::post('add_sub_services', 'App\Http\Controllers\SubServicesController@add_sub_services');
    Route::delete('delete_sub_services/{id}', 'App\Http\Controllers\SubServicesController@delete_sub_services');
    
    Route::get('estimates', 'App\Http\Controllers\EstimateController@estimates');
    Route::post('add_estimate', 'App\Http\Controllers\EstimateController@add_estimate');
    Route::post('update_estimate', 'App\Http\Controllers\EstimateController@update_estimate');
    Route::post('convert_estimate', 'App\Http\Controllers\EstimateController@convert_estimate');
    Route::delete('delete_estimate/{id}', 'App\Http\Controllers\EstimateController@delete_estimate');
    Route::post('add_estimate_save_send', 'App\Http\Controllers\EstimateController@add_estimate_save_send');
    Route::post('update_status_estimate', 'App\Http\Controllers\EstimateController@update_status_estimate');
    Route::get('find_estimates/{id}', 'App\Http\Controllers\EstimateController@find_estimates');
    Route::get('find_estimate_customer/{id}', 'App\Http\Controllers\EstimateController@find_estimate_customer');
    Route::get('find_sub_services/{id}', 'App\Http\Controllers\EstimateController@find_sub_services');
    Route::get('sub_services/{id}', 'App\Http\Controllers\EstimateController@sub_services');
    Route::get('estimate_count', 'App\Http\Controllers\EstimateController@estimate_count');
    Route::post('send_estimate_to_loa', 'App\Http\Controllers\EstimateController@send_estimate_to_loa');
    

    Route::get('find_property/{id}', 'App\Http\Controllers\PropertyController@find_property');
    Route::post('add_property', 'App\Http\Controllers\PropertyController@add_property');
    Route::delete('delete_property/{id}', 'App\Http\Controllers\PropertyController@delete_property');

    Route::get('vehicles', 'App\Http\Controllers\VehicleController@vehicles');
    Route::post('add_vehicle', 'App\Http\Controllers\VehicleController@add_vehicle');
    Route::delete('delete_vehicle/{id}', 'App\Http\Controllers\VehicleController@delete_vehicle');
    

    Route::get('insurance', 'App\Http\Controllers\InsuranceController@insurance');
    Route::get('find_insurance/{id}', 'App\Http\Controllers\InsuranceController@find_insurance');
    Route::post('add_insurance', 'App\Http\Controllers\InsuranceController@add_insurance');
    Route::delete('delete_insurance/{id}', 'App\Http\Controllers\InsuranceController@delete_insurance');
    
    Route::get('supplier', 'App\Http\Controllers\SupplierController@supplier');
    Route::post('add_supplier', 'App\Http\Controllers\SupplierController@add_supplier');
    Route::delete('delete_supplier/{id}', 'App\Http\Controllers\SupplierController@delete_supplier');

    Route::get('get_customer_profile/{id}', 'App\Http\Controllers\CustomerController@get_customer_profile');

    Route::get('document_download/{file_name}', 'App\Http\Controllers\DocumentController@document_download');
    Route::get('find_documents/{id}', 'App\Http\Controllers\DocumentController@find_documents');
    Route::get('find_loa_documents/{id}', 'App\Http\Controllers\DocumentController@find_loa_documents');
    Route::post('add_documents', 'App\Http\Controllers\DocumentController@add_documents');
    Route::post('add_loa_documents', 'App\Http\Controllers\DocumentController@add_loa_documents');
    Route::delete('delete_loa_document/{id}/{estimate_id}', 'App\Http\Controllers\DocumentController@delete_loa_document');
    Route::delete('delete_document/{id}/{estimate_id}', 'App\Http\Controllers\DocumentController@delete_document');
    
    Route::delete('delete_job_order_document/{id}/{estimate_id}', 'App\Http\Controllers\DocumentController@delete_job_order_document');
    Route::delete('delete_job_order_loa_document/{id}/{estimate_id}', 'App\Http\Controllers\DocumentController@delete_job_order_loa_document');

    Route::post('add_job_order_documents', 'App\Http\Controllers\DocumentController@add_job_order_documents');
    Route::post('add_job_order_loa_documents', 'App\Http\Controllers\DocumentController@add_job_order_loa_documents');

    Route::get('personnels', 'App\Http\Controllers\PersonnelController@personnels');
    Route::post('add_personnel', 'App\Http\Controllers\PersonnelController@add_personnel');
    Route::delete('delete_personnel/{id}', 'App\Http\Controllers\PersonnelController@delete_personnel');

    Route::get('personnel_types', 'App\Http\Controllers\PersonnelTypeController@personnel_types');
    Route::post('add_personnel_type', 'App\Http\Controllers\PersonnelTypeController@add_personnel_type');
    Route::delete('delete_personnel_type/{id}', 'App\Http\Controllers\PersonnelTypeController@delete_personnel_type');

    Route::post('upload_loa_document', 'App\Http\Controllers\LoaDocumentController@upload_loa_document');
    Route::get('/uploader/{estimate}', function (Request $request) {
        if (! $request->hasValidSignature()) {
            abort(401);
        }
        return response()->json(200);
    })->name('uploader');
    
    Route::get('purchases', 'App\Http\Controllers\PurchaseController@purchases');
    Route::post('add_purchases', 'App\Http\Controllers\PurchaseController@add_purchases');
    Route::post('edit_purchases', 'App\Http\Controllers\PurchaseController@edit_purchases');
    Route::delete('delete_purchase/{id}/{job_order_id}', 'App\Http\Controllers\PurchaseController@delete_purchase');
    Route::delete('delete_purchase_item/{id}', 'App\Http\Controllers\PurchaseController@delete_purchase_item');
    
    Route::get('expenses_type', 'App\Http\Controllers\ExpensesTypeController@expenses_type');
    Route::post('create_expenses_type', 'App\Http\Controllers\ExpensesTypeController@create_expenses_type');
    Route::delete('delete_expenses_type/{id}', 'App\Http\Controllers\ExpensesTypeController@delete_expenses_type');

    Route::get('agents', 'App\Http\Controllers\AgentController@agents');
    Route::post('create_agent', 'App\Http\Controllers\AgentController@create_agent');
    Route::delete('delete_agent/{id}', 'App\Http\Controllers\AgentController@delete_agent');
    
    
    Route::get('payments', 'App\Http\Controllers\PaymentController@payments');
    Route::get('payment_list', 'App\Http\Controllers\PaymentController@payment_list');
    Route::post('add_payment', 'App\Http\Controllers\PaymentController@add_payment');
    Route::post('update_payment', 'App\Http\Controllers\PaymentController@update_payment');
    Route::delete('delete_payment/{id}/{billing_statement_id}', 'App\Http\Controllers\PaymentController@delete_payment');
    Route::delete('delete_payment/{id}', 'App\Http\Controllers\PaymentController@delete_payment_list');
    
    Route::get('gate_pass_no', 'App\Http\Controllers\GatePassController@gate_pass_no');
    Route::post('submit_gatepass', 'App\Http\Controllers\GatePassController@submit_gatepass');
    Route::post('delete_gatepass', 'App\Http\Controllers\GatePassController@delete_gatepass');
    
    Route::get('cash_collected', 'App\Http\Controllers\ChartController@cash_collected');
    Route::get('cash_collectables', 'App\Http\Controllers\ChartController@cash_collectables');
    
    Route::get('weekly_payment', 'App\Http\Controllers\PaymentController@weekly_payment');
    
    Route::get('todos', 'App\Http\Controllers\TodoController@todos');
    Route::post('add_todo', 'App\Http\Controllers\TodoController@add_todo');
    Route::post('update_todo', 'App\Http\Controllers\TodoController@update_todo');
    Route::post('update_status_todo', 'App\Http\Controllers\TodoController@update_status_todo');
    Route::delete('delete_todo/{id}', 'App\Http\Controllers\TodoController@delete_todo');
    
    Route::get('notifications', 'App\Http\Controllers\NotificationController@notifications');
    
    Route::get('cash_collected_report/{period}', 'App\Http\Controllers\ReportController@cash_collected_report');
    Route::get('cash_collected_report/{period}/{from}/{to}', 'App\Http\Controllers\ReportController@cash_collected_period_report');
    
    Route::get('cash_collectables_report/{period}', 'App\Http\Controllers\ReportController@cash_collectables_report');
    Route::get('cash_collectables_report/{period}/{from}/{to}', 'App\Http\Controllers\ReportController@cash_collectables_period_report');

    Route::post('generate_report', 'App\Http\Controllers\ReportController@generate_report');

    Route::post('create_billing', 'App\Http\Controllers\BillingController@create_billing');
    Route::get('find_billings/{id}', 'App\Http\Controllers\BillingController@find_billings');
    Route::get('get_billing_statement_no', 'App\Http\Controllers\BillingController@get_billing_statement_no');
    Route::get('get_billing_statement', 'App\Http\Controllers\BillingController@get_billing_statement');
    Route::delete('delete_billing_statement/{id}', 'App\Http\Controllers\BillingController@delete_billing_statement');
    Route::post('submit_payment', 'App\Http\Controllers\BillingController@submit_payment');
    
    
    
});
