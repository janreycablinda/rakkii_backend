<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScopeOfWorkSubServices extends Model
{
    use HasFactory;

    public function sub_services(){
        return $this->hasOne(\App\Models\SubServices::class, 'id', 'sub_services_id');
    }

    public function sub_services_items(){
        return $this->belongsToMany(\App\Models\ScopeOfWorkServices::class, 'id', 'scope_of_work_services_id');
    }
}
