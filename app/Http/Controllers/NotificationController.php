<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function notifications()
    {
        $get = Notification::orderBy('id', 'DESC')->latest()->limit(10)->get();

        return response()->json($get);
    }
}
