<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GatePass extends Model
{
    use HasFactory;

    public static function boot()
    {
        parent::boot();

        static::creating(function($model) {
            $model->gate_pass_no = GatePass::max('gate_pass_no') +1;
        });
    }

    public function user(){
        return $this->hasOne(\App\Models\User::class, 'id', 'user_id');
    }
}
