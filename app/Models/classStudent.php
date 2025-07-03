<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class classStudent extends Model
{
    use SoftDeletes;

    protected $table = 'class_student';
    protected $dates = ['deleted_at'];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
