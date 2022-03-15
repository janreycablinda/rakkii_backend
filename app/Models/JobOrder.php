<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobOrder extends Model
{
    use HasFactory;

    public static function boot()
    {
        parent::boot();

        static::creating(function($model) {
            $model->job_order_no = JobOrder::max('job_order_no') +1;
        });
    }

    public function payments(){
        return $this->hasMany(\App\Models\Payment::class, 'job_order_id', 'id');
    }

    public function customer(){
        return $this->hasOne(\App\Models\Customer::class, 'id', 'customer_id');
    }

    public function property(){
        return $this->hasOne(\App\Models\CarProperty::class, 'id', 'vehicle_id');
    }

    public function insurance(){
        return $this->hasOne(\App\Models\Insurance::class, 'id', 'insurance_id');
    }

    public function scope(){
        return $this->hasMany(\App\Models\JobOrderScopeServices::class, 'job_order_id', 'id');
    }

    public function documents(){
        return $this->hasMany(\App\Models\JobOrderDocument::class, 'job_order_id', 'id');
    }

    public function loa_documents(){
        return $this->hasMany(\App\Models\JobOrderLoaDocument::class, 'job_order_id', 'id');
    }

    public function activity_log()
    {
        return $this->hasMany(\App\Models\JobOrderActivityLog::class, 'job_order_id', 'id');
    }

    public function timeline()
    {
        return $this->hasMany(\App\Models\Timeline::class, 'job_order_id', 'id');
    }

    public function mail()
    {
        return $this->hasMany(\App\Models\JobOrderMailTrack::class, 'job_order_id', 'id');
    }

    public function payables()
    {
        return $this->hasOne(\App\Models\Payable::class, 'job_order_id', 'id');
    }

    public function purchases()
    {
        return $this->hasMany(\App\Models\Purchase::class, 'job_order_id', 'id');
    }

    public function other_expenses()
    {
        return $this->hasMany(\App\Models\OtherExpense::class, 'job_order_id', 'id');
    }

    public function gatepass(){
        return $this->hasOne(\App\Models\GatePass::class, 'job_order_id', 'id');
    }
    
    public function billings()
    {
        return $this->hasMany(\App\Models\BillingStatement::class, 'job_order_id', 'id');
    }
}
