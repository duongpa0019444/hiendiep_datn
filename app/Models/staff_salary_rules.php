<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class staff_salary_rules extends Model
{
    
    protected $table = 'staff_salary_rules';

    protected $fillable = [
      'staff_id',
      'base_salary',
      'salary_coefficient',
      'insurance',
      'end_pay_rate',
      'start_pay_rate',
    ];

}
