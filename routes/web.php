<?php

use Illuminate\Support\Facades\Route;
use Carbon\Carbon;
use App\Events\RealTimeNotification;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    // dd(Carbon::now()->subDays(7)->startOfWeek());
    // dd(Carbon::now()->endOfWeek());
    // dd(Carbon::now()->subDays(6));
    return view('welcome');
});

Route::get('broadcast', function() {
    broadcast(new RealTimeNotification());
});

Route::get('item/export/', 'App\Http\Controllers\ImportController@export');

Route::get('test', 'App\Http\Controllers\HomeController@test');


