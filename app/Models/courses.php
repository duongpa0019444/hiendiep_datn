<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class courses extends Model
{
    //
    protected $table = 'courses';

    protected $fillable = ['name', 'price', 'total_sessions', 'description'];

    // Quan hệ với bảng course_payment
    public function payments()
    {
        return $this->hasMany(CoursePayment::class, 'course_id');
    }
}
