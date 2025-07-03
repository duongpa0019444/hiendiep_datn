<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class questions extends Model
{
    //
    protected $table = 'questions';

    protected $fillable = [
        'quiz_id',
        'content',
        'type',
        'points',
        'explanation'
    ];

     public function quiz()
    {
        return $this->belongsTo(Quizzes::class, 'quiz_id');
    }

    public function answers()
    {
        return $this->hasMany(answers::class, 'question_id');
    }

    public function studentAnswers()
    {
        return $this->hasMany(studentAnswers::class, 'question_id');
    }
}
