<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class classes extends Model
{
    protected $table = 'classes';

    use SoftDeletes;
    protected $dates = ['deleted_at']; // Cột lưu thời gian xóa mềm

    protected $fillable = ['name', 'courses_id', 'number_of_sessions', 'status', 'created_at', 'updated_at'];

    public function students()
    {
        return $this->belongsToMany(User::class, 'class_student', 'class_id', 'student_id');
    }

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
