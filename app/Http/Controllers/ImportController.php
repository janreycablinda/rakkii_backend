<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\ItemImport;
use App\Exports\ItemExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\File;

class ImportController extends Controller
{
    public function import_item(Request $request)
    {
         $request->validate([
            'import_file' => 'required|file|mimes:xls,xlsx,csv,txt'
        ]);

        $path = $request->file('import_file');
        
        $data = Excel::import(new ItemImport, $path);

        return response()->json(['message' => 'uploaded successfully'], 200);
    }

    public function export() 
    {
        return Excel::download(new ItemExport, 'item.xlsx');
    }
}
