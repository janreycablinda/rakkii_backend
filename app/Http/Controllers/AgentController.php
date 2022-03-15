<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agent;

class AgentController extends Controller
{
    public function agents()
    {
        $get = Agent::where('is_deleted', false)->get();

        return response()->json($get);
    }

    public function create_agent(Request $request)
    {
        $new = new Agent;
        $new->name = $request->name;
        $new->address = $request->address;
        $new->contact_no = $request->contact_no;
        $new->save();

        return response()->json($new);
    }

    public function delete_agent($id)
    {
        $del = Agent::where('id', $id)->update([
            'is_deleted' => true
        ]);

        return response()->json(200);
    }
}
