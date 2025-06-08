<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class classes extends Model
{

    // Quan hệ với khóa học (courses)
    public function course()
    {
        return $this->belongsTo(courses::class, 'courses_id', 'id');
    }

    // Quan hệ với giáo viên (users)
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id', 'id');
    }

    // Quan hệ với học sinh (class_student)
    public function classStudents()
    {
        return $this->hasMany(ClassStudent::class, 'class_id', 'id');
    }

    //Quan hệ với bảng course_payment
    public function payments()
    {
        return $this->hasMany(CoursePayment::class, 'class_id');
    }
}
