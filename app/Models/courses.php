<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class courses extends Model
{
    //
    protected $table = 'courses';

    protected $fillable = ['name','image', 'price', 'total_sessions', 'description','teaching_method','teaching_goals'];

    // Quan hệ với bảng course_payment
    public function payments()
    {
        return $this->hasMany(CoursePayment::class, 'course_id');
    }
    public function course(){
        return self::all();

    }
    public function getCourseById($id)
    {
        return self::find($id);
    }
    
}
