<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Services;

class ServicesController extends Controller
{
    public function services()
    {
        $get = Services::with(['sub_services' => function ($query) {
            $query->where('is_deleted', false);
        }],['services_type' => function ($query) {
            $query->where('is_deleted', false);
        }])->where('is_deleted', false)->get();

        return response()->json($get);
    }

    public function add_services(Request $request)
    {
        $new = new Services;
        $new->services_type_id = $request->services_type_id;
        $new->services_name = $request->services_name;
        $new->save();

        return response()->json($new->load(['sub_services' => function ($query) {
            $query->where('is_deleted', false);
        }],['services_type' => function ($query) {
            $query->where('is_deleted', false);
        }]));
    }

    public function find_services($id)
    {
        $find = Services::with(['sub_services' => function ($query) {
            $query->where('is_deleted', false);
        }],['services_type' => function ($query) {
            $query->where('is_deleted', false);
        }])->find($id);

        return response()->json($find);
    }

    public function delete_services($id)
    {
        $del = Services::where('id', $id)->update([
            'is_deleted' => true
        ]);

        return response()->json(200);
    }
}
