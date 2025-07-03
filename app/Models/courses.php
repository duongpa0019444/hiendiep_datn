<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class courses extends Model
{
    //
    protected $table = 'courses';

    protected $fillable = ['name','image', 'price', 'total_sessions', 'description','teaching_method','teaching_goals','is_featured'];

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

    // quan hệ với lớp học
    public function classes()
    {
        return $this->hasMany(Classes::class, 'courses_id');
    }

    // lấy số lượng học sinh đã đăng ký khóa học
    /**
     * Accessor: Đếm tổng số học sinh trong các lớp thuộc khóa học này
     *
     * - Dựa trên danh sách class_id đã eager-loaded qua with('classes')
     * - Đếm số dòng student_id trong bảng class_student tương ứng
     */
    public function getStudentCountAttribute(): int
    {
        // Lấy toàn bộ class_id thuộc course hiện tại
        $classIds = $this->classes->pluck('id');

        if ($classIds->isEmpty()) {
            return 0;
        }

        // Đếm số student_id thuộc các class_id này
        return classStudent::whereIn('class_id', $classIds)
            ->count('student_id');
    }

    // lấy  slug
    public function getRouteKeyName()
    {
        return 'slug'; // hoặc 'name' nếu unique
    }



    // // quan hệ với giáo viên
    // public function teachers()
    // {
    //     return $this->belongsToMany(User::class, 'teacher_salary_rules', 'teacher_id');
    // }

    public function course(){
        return self::all();

    }
    public function getCourseById($id)
    {
        return self::find($id);
    }
    

}
