<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    public function billings(){
        return $this->hasOne(\App\Models\Billing::class, 'customer_id', 'id');
    }

    public function shippings(){
        return $this->hasOne(\App\Models\Shipping::class, 'customer_id', 'id');
    }

    public function user(){
        return $this->hasOne(\App\Models\User::class, 'id', 'user_id');
    }
}
