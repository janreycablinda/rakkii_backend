<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stockin_temp extends Model
{
    use HasFactory;

    public function stockin()
    {
        return $this->hasOne(\App\Models\Stockin::class, 'id', 'stockin_id');
    }

    public function item()
    {
        return $this->hasOne(\App\Models\Item::class, 'id', 'item_id');
    }
}
