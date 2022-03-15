<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Payment;

class Payment extends Model
{
    use HasFactory;

    public static function boot()
    {
        parent::boot();

        static::creating(function($model) {
            $model->receipt_no = Payment::max('receipt_no') +1;
        });
    }

    public function user(){
        return $this->hasOne(\App\Models\User::class, 'id', 'user_id');
    }

    public function job_order(){
        return $this->hasOne(\App\Models\JobOrder::class, 'id', 'job_order_id');
    }
}
