<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class teacher_salary_rules extends Model
{
    
    protected $table = 'teacher_salary_rules';

    protected $fillable = [
      'teacher_id',
      'pay_rate',
      'unit',
      'end_pay_rate',
      'effective_date',
    ];

}
