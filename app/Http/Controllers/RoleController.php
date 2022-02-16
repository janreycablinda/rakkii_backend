<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;
use App\Models\PermissionRole;

class RoleController extends Controller
{
    public function roles()
    {
        $get = Role::with('permissions.permission')->where('is_deleted', false)->get();

        return response()->json($get);
    }

    public function role_options()
    {
        $get = Permission::all();

        return response()->json($get);
    }

    public function add_role(Request $request)
    {

        $new = new Role;
        $new->role_name = $request->role_name;
        $new->save();

        foreach($request->permissions as $perm){
            $new_permission = new PermissionRole;
            $new_permission->role_id = $new->id;
            $new_permission->permission_id = $perm['value'];
            $new_permission->save();
        }
        
        return response()->json($new->load('permissions.permission'));
    }

    public function update_role(Request $request)
    {
        $update = Role::where('id', $request->id)->update([
            'role_name' => $request->role_name
        ]);

        $delete = PermissionRole::where('role_id', $request->id)->delete();

        foreach($request->permissions as $perm){
            $new_permission = new PermissionRole;
            $new_permission->role_id = $request->id;
            $new_permission->permission_id = $perm;
            $new_permission->save();
        }

        return response()->json(Role::with('permissions.permission')->where('id', $request->id)->first());
    }

    public function delete_role($id)
    {
        $delete = Role::where('id', $id)->update([
            'is_deleted' => true
        ]);

        return response()->json(200);
    }
}
