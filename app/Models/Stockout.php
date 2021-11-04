<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stockout extends Model
{
    use HasFactory;

    public function customer()
    {
        return $this->hasOne(\App\Models\Customer::class, 'id', 'customer_id');
    }

    public function stockout_temps()
    {
        return $this->hasMany(\App\Models\Stockout_temp::class, 'stockout_id', 'id');
    }

    public function user()
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'user_id');
    }
}
