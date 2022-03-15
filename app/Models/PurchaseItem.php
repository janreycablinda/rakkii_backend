<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    use HasFactory;

    public function item(){
        return $this->hasOne(\App\Models\Item::class, 'id', 'item_id');
    }

    public function unit(){
        return $this->hasOne(\App\Models\Unit::class, 'id', 'unit_id');
    }
}
