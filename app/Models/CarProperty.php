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
}
