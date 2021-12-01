<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estimate extends Model
{
    use HasFactory;

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
        return $this->hasMany(\App\Models\ScopeOfWorkServices::class, 'estimate_id', 'id');
    }

    public function documents(){
        return $this->hasMany(\App\Models\Document::class, 'estimate_id', 'id');
    }
    
    public static function boot()
    {
        parent::boot();

        static::creating(function($model) {
            $model->estimate_no = Estimate::max('estimate_no') +1;
            // $model->estimate_no = 'EST-' . str_pad($model->number, 5, 0, STR_PAD_LEFT);
        });
    }

    public function activity_log()
    {
        return $this->hasMany(\App\Models\EstimateActivityLog::class, 'estimate_id', 'id');
    }
}
