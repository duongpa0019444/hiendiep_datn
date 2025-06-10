<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\classes;
use App\Models\courses;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quizzes extends Model
{
    use SoftDeletes;
    protected $table = 'quizzes';

    protected $fillable = [
        'title',
        'description',
        'status',
        'access_code',
        'is_public',
        'duration_minutes',
        'class_id',
        'course_id',
        'created_by',
    ];

    public function class()
    {
        return $this->belongsTo(classes::class, 'class_id');
    }

    public function course()
    {
        return $this->belongsTo(courses::class, 'course_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
