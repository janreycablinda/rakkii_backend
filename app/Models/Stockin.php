<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stockin extends Model
{
    use HasFactory;

    public function stockin_temps()
    {
        return $this->hasMany(\App\Models\Stockin_temp::class, 'stockin_id', 'id');
    }

    public function user()
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'user_id');
    }
}
