<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class quizAttempts extends Model
{
    //

    protected $table = 'quiz_attempts';

    protected $fillable = [
        'quiz_id',
        'user_id',
        'started_at',
        'submitted_at',
        'score',
        'total_correct',
        'total_questions',
        'class_id'
    ];

    protected $dates = [
        'started_at',
        'submitted_at',
        'deleted_at'
    ];

    // Relationships
    public function quiz()
    {
        return $this->belongsTo(Quizzes::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function class()
    {
        return $this->belongsTo(classes::class, 'class_id');
    }
}
