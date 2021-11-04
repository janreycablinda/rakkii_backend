<?php

namespace App\Imports;

use App\Models\Item;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ItemImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Item([
            'item'  => $row['item'],
            'description' => $row['description'],
            'brand' => $row['brand'],
            'unit' => $row['unit'],
            'price' => $row['price'],
            'unit_cost' => $row['unit_cost'],
            'qty' => $row['qty'],
            'notifier' => $row['notifier'],
            'group_id' => $row['group_id'],
            'user_id' => $row['user_id'],
        ]);
    }
}
