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

    // quan hệ với bài giảng
    public function lessons()
    {
        return $this->hasMany(lessons::class, 'course_id');
    }

    // quan hệ với học sinh
    public function students()
    {
        return $this->belongsToMany(User::class, 'class_student', 'class_id', 'student_id');
    }
    // // quan hệ với giáo viên
    // public function teachers()
    // {
    //     return $this->belongsToMany(User::class, 'teacher_salary_rules', 'teacher_id');
    // }
}
