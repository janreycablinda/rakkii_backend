<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarProperty extends Model
{
    use HasFactory;

    public function vehicle(){
        return $this->hasOne(\App\Models\Vehicle::class, 'id', 'vehicle_id');
    }

    public function customer(){
        return $this->hasOne(\App\Models\Customer::class, 'id', 'customer_id');
    }
}
