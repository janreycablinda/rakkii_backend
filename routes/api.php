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

    Route::get('units', 'App\Http\Controllers\UnitController@units');
    Route::get('get_country', 'App\Http\Controllers\CountryController@get_country');

    Route::get('groups', 'App\Http\Controllers\GroupController@groups');
    Route::post('create_group', 'App\Http\Controllers\GroupController@create_group');
    Route::put('update_group', 'App\Http\Controllers\GroupController@update_group');
    Route::delete('delete_group/{id}', 'App\Http\Controllers\GroupController@delete_group');
    
    // Route::get('items', 'App\Http\Controllers\ItemController@items');
    // Route::post('create_item', 'App\Http\Controllers\ItemController@create_item');
    // Route::put('update_item', 'App\Http\Controllers\ItemController@update_item');
    // Route::delete('delete_item/{id}', 'App\Http\Controllers\ItemController@delete_item');

    Route::get('job_orders', 'App\Http\Controllers\JobOrderController@job_orders');

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
    
    

    Route::get('services', 'App\Http\Controllers\ServicesController@services');
    Route::post('add_services', 'App\Http\Controllers\ServicesController@add_services');
    Route::get('find_services/{id}', 'App\Http\Controllers\ServicesController@find_services');

    Route::post('add_sub_services', 'App\Http\Controllers\SubServicesController@add_sub_services');
    
    Route::get('estimates', 'App\Http\Controllers\EstimateController@estimates');
    Route::post('add_estimate', 'App\Http\Controllers\EstimateController@add_estimate');
    Route::post('update_estimate', 'App\Http\Controllers\EstimateController@update_estimate');
    Route::post('convert_estimate', 'App\Http\Controllers\EstimateController@convert_estimate');
    Route::delete('delete_estimate/{id}', 'App\Http\Controllers\EstimateController@delete_estimate');
    Route::post('add_estimate_save_send', 'App\Http\Controllers\EstimateController@add_estimate_save_send');
    Route::post('update_status_estimate', 'App\Http\Controllers\EstimateController@update_status_estimate');
    Route::get('find_estimates/{id}', 'App\Http\Controllers\EstimateController@find_estimates');
    Route::get('find_sub_services/{id}', 'App\Http\Controllers\EstimateController@find_sub_services');
    Route::get('sub_services/{id}', 'App\Http\Controllers\EstimateController@sub_services');

    Route::get('find_property/{id}', 'App\Http\Controllers\PropertyController@find_property');
    Route::post('add_property', 'App\Http\Controllers\PropertyController@add_property');

    Route::get('vehicles', 'App\Http\Controllers\VehicleController@vehicles');
    Route::post('add_vehicle', 'App\Http\Controllers\VehicleController@add_vehicle');

    Route::get('insurance', 'App\Http\Controllers\InsuranceController@insurance');
    Route::post('add_insurance', 'App\Http\Controllers\InsuranceController@add_insurance');
    Route::delete('delete_insurance/{id}', 'App\Http\Controllers\InsuranceController@delete_insurance');
    
    Route::get('supplier', 'App\Http\Controllers\SupplierController@supplier');
    Route::post('add_supplier', 'App\Http\Controllers\SupplierController@add_supplier');
    Route::delete('delete_supplier/{id}', 'App\Http\Controllers\SupplierController@delete_supplier');

    Route::get('get_customer_profile/{id}', 'App\Http\Controllers\CustomerController@get_customer_profile');

    Route::get('document_download/{file_name}', 'App\Http\Controllers\DocumentController@document_download');
});
