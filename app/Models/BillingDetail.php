<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillingDetail extends Model
{
    use HasFactory;

    public function services(){
        return $this->hasOne(\App\Models\Services::class, 'id', 'services_id');
    }

    public function sub_services(){
        return $this->hasOne(\App\Models\SubServices::class, 'id', 'sub_services_id');
    }
}
