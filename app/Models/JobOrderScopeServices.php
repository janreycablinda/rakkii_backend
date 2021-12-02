<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobOrderScopeServices extends Model
{
    use HasFactory;

    public function services(){
        return $this->hasOne(\App\Models\Services::class, 'id', 'services_id');
    }

    public function sub_services(){
        return $this->hasMany(\App\Models\JobOrderScopeSubServices::class, 'job_order_scope_services_id', 'id');
    }
}
