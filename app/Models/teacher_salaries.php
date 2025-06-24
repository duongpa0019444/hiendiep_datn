<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class teacher_salaries extends Model
{
    
    protected $table = 'teacher_salaries';

    protected $fillable = [
        'month',
        'year',
        'teacher_id',
        'total_hours',
        'total_salary',
        'bonus',
        'penalty',
        'paid',
        'payment_date',
        'note',
        'created_at',
        'updated_at',
    ];

}
