<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubServices extends Model
{
    use HasFactory;

    public function services(){
        return $this->hasOne(\App\Models\Services::class, 'id', 'services_id');
    }
}
