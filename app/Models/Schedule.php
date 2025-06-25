<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $table = 'schedules';

    protected $fillable = [
        'date',
        'start_time',
        'end_time',
        'teacher_id',
        'class_id'
    ];
    
}
