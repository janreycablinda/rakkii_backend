<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    public function supplier(){
        return $this->hasOne(\App\Models\Supplier::class, 'id', 'supplier_id');
    }

    public function receipts(){
        return $this->hasMany(\App\Models\PurchaseReceipt::class, 'purchase_id', 'id');
    }

    public function purchase_items(){
        return $this->hasMany(\App\Models\PurchaseItem::class, 'purchase_id', 'id');
    }
}
