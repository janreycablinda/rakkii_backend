<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;

class GroupController extends Controller
{
    public function groups()
    {
        $groups = Group::where('is_deleted', false)->get();

        return response()->json($groups);
    }

    public function create_group(Request $request)
    {
        $new = new Group();
        $new->group_name = $request->group_name;
        $new->save();

        return response()->json($new);
    }

    public function update_group(Request $request)
    {
        $update = Group::where('id', $request->id)->update([
            'group_name' => $request->group_name
        ]);

        return response()->json($request);
    }

    public function delete_group($id)
    {
        $delete = Group::where('id', $id)->update([
            'is_deleted' => true
        ]);

        return response()->json(200);
    }
}
