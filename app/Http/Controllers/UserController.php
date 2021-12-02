<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function users()
    {
        $get = User::with('role')->where('is_deleted', false)->get();

        return response()->json($get);
    }
}
