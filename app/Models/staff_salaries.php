<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class staff_salaries extends Model
{
    
    protected $table = 'staff_salaries';

    protected $fillable = [
        'month',
        'year',
        'staff_id',
        'insurance_fee',
        'bonus',
        'penalty',
        'total_salary',
        'paid',
        'payment_date',
        'note',
        'created_at',
        'updated_at',
    ];

}
