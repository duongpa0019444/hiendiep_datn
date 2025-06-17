<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class classStudent extends Model
{
    protected $table = 'class_student';
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
