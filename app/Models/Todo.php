<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Todo;

class Todo extends Model
{
    use HasFactory;

    public static function boot()
    {
        parent::boot();

        static::creating(function($model) {
            $model->todo_order_no = Todo::where('status', 'todo')->max('todo_order_no') +1;
        });
    }
}
