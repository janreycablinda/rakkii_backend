<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personnel extends Model
{
    use HasFactory;

    public function personnel_type(){
        return $this->hasOne(\App\Models\PersonnelType::class, 'id', 'personnel_type_id');
    }
}
