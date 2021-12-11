<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timeline extends Model
{
    use HasFactory;

    public function services_type(){
        return $this->hasOne(\App\Models\Services_type::class, 'id', 'services_type_id');
    }

    public function personnel(){
        return $this->hasOne(\App\Models\Personnel::class, 'id', 'personnel_id');
    }
}
