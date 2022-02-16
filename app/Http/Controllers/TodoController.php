<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo;

class TodoController extends Controller
{
    public function todos()
    {
        $get = Todo::orderBy('todo_order_no', 'ASC')->where('user_id', auth()->user()->id)->get();

        return response()->json($get);
    }

    public function add_todo(Request $request)
    {
        $add = new Todo;
        $add->todo = $request->todo;
        $add->status = 'todo';
        $add->user_id = auth()->user()->id;
        $add->save();

        return response()->json($add);
    }

    public function update_todo(Request $request)
    {
        foreach($request->todo as $key => $todo){
            $update = Todo::where('id', $todo['id'])->update([
                'todo_order_no' => $key+1
            ]);
        }
        return response()->json(200);
    }

    public function update_status_todo(Request $request)
    {
        $find = Todo::find($request->id);
        if($find->status == 'todo'){
            $update = Todo::where('id', $request->id)->update([
                'status' => 'finished'
            ]);
        }else{
            $update = Todo::where('id', $request->id)->update([
                'status' => 'todo'
            ]);
        }
        return response()->json(200);
    }

    public function delete_todo($id)
    {
        $delete = Todo::find($id)->delete();

        return response()->json(200);
    }
}
