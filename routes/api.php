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

    Route::get('units', 'App\Http\Controllers\UnitController@units');

    Route::get('groups', 'App\Http\Controllers\GroupController@groups');
    Route::post('create_group', 'App\Http\Controllers\GroupController@create_group');
    Route::put('update_group', 'App\Http\Controllers\GroupController@update_group');
    Route::delete('delete_group/{id}', 'App\Http\Controllers\GroupController@delete_group');
    
    Route::get('items', 'App\Http\Controllers\ItemController@items');
    Route::post('create_item', 'App\Http\Controllers\ItemController@create_item');
    Route::put('update_item', 'App\Http\Controllers\ItemController@update_item');
    Route::delete('delete_item/{id}', 'App\Http\Controllers\ItemController@delete_item');

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
});
