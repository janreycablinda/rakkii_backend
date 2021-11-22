<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estimate extends Model
{
    use HasFactory;

    public function customer(){
        return $this->hasOne(\App\Models\Customer::class, 'id', 'customer_id');
    }

    public function property(){
        return $this->hasOne(\App\Models\CarProperty::class, 'id', 'vehicle_id');
    }

    public function insurance(){
        return $this->hasOne(\App\Models\Insurance::class, 'id', 'insurance_id');
    }

    public function scope(){
        return $this->hasMany(\App\Models\ScopeOfWorkServices::class, 'estimate_id', 'id');
    }
    
}
