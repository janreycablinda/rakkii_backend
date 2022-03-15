<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillingPayment extends Model
{
    use HasFactory;

    protected $fillable = ['amount'];

    public static function boot()
    {
        parent::boot();

        static::creating(function($model) {
            $model->receipt_no = BillingPayment::max('receipt_no') +1;
        });
    }

    public function user(){
        return $this->hasOne(\App\Models\User::class, 'id', 'user_id');
    }
}
