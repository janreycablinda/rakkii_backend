<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Services extends Model
{
    use HasFactory;
    
    public function services_type(){
        return $this->hasOne(\App\Models\Services_type::class, 'id', 'services_type_id');
    }

    public function sub_services(){
        return $this->hasMany(\App\Models\SubServices::class, 'services_id', 'id');
    }
}
