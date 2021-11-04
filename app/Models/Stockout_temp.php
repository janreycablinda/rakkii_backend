<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stockout_temp extends Model
{
    use HasFactory;

    public function stockout()
    {
        return $this->hasOne(\App\Models\Stockout::class, 'id', 'stockout_id');
    }

    public function item()
    {
        return $this->hasOne(\App\Models\Item::class, 'id', 'item_id');
    }
}
