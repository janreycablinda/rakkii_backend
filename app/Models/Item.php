<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'item',
        'description',
        'brand',
        'unit',
        'price',
        'unit_cost',
        'qty',
        'notifier',
        'group_id',
        'user_id',
    ];

    public function user()
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'user_id');
    }

    public function group()
    {
        return $this->hasOne(\App\Models\Group::class, 'id', 'group_id');
    }

    public function stockin_temp()
    {
        return $this->hasMany(\App\Models\Stockin_temp::class, 'item_id', 'id');
    }

    public function stockout_temp()
    {
        return $this->hasMany(\App\Models\Stockout_temp::class, 'item_id', 'id');
    }
}
