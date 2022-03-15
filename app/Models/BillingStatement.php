<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillingStatement extends Model
{
    use HasFactory;

    public static function boot()
    {
        parent::boot();

        static::creating(function($model) {
            $model->billing_statement_no = BillingStatement::max('billing_statement_no') +1;
        });
    }

    public function billing_payment(){
        return $this->hasMany(\App\Models\BillingPayment::class, 'billing_statement_id', 'id');
    }

    public function billing_details(){
        return $this->hasMany(\App\Models\BillingDetail::class, 'billing_statement_id', 'id');
    }

    public function job_order(){
        return $this->hasOne(\App\Models\JobOrder::class, 'id', 'job_order_id');
    }

    public function customer(){
        return $this->hasOne(\App\Models\Customer::class, 'id', 'customer_id');
    }

    public function insurance(){
        return $this->hasOne(\App\Models\Insurance::class, 'id', 'insurance_id');
    }
}
