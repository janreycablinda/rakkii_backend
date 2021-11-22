<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScopeOfWorkServices extends Model
{
    use HasFactory;

    public function services(){
        return $this->hasOne(\App\Models\Services::class, 'id', 'services_id');
    }

    public function sub_services(){
        return $this->hasMany(\App\Models\ScopeOfWorkSubServices::class, 'scope_of_work_services_id', 'id');
    }
}
