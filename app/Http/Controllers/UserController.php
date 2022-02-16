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

    public function add_user(Request $request)
    {
        $new = new User;
        $new->name = $request->name;
        $new->username = $request->username;
        $new->email = $request->email;
        $new->password = bcrypt($request->password);
        $new->role_id = $request->role_id;
        $new->save();

        return response()->json($new->load('role'));
    }

    public function update_user(Request $request)
    {
        if($request->password){
            $update = User::where('id', $request->id)->update([
                'name' => $request->name,
                'username' => $request->username,
                'password' => bcrypt($request->username),
                'email' => $request->email,
                'role_id' => $request->role_id,
            ]);
        }else{
            $update = User::where('id', $request->id)->update([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'role_id' => $request->role_id,
            ]);
        }
        
        return response()->json(User::with('role')->where('id', $request->id)->first());
    }

    public function delete_user($id)
    {
        $delete = User::where('id', $id)->update([
            'is_deleted' => true
        ]);

        return response()->json(200);
    }
}
