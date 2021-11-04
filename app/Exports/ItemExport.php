<?php

namespace App\Exports;

use App\Models\Item;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Excel;

class ItemExport implements FromCollection
{
    use Exportable;
    /**
    * @return \Illuminate\Support\Collection
    */

    private $fileName = 'invoices.xlsx';
    
    /**
    * Optional Writer Type
    */
    private $writerType = Excel::XLSX;
    
    /**
    * Optional headers
    */
    private $headers = [
        'Content-Type' => 'text/csv',
    ];

    public function collection()
    {
        return Item::all();
    }
}
