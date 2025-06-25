<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class sentenceQuestions extends Model
{
    //
    protected $table = 'sentence_questions';
    protected $fillable = [
        'quiz_id',
        'type',
        'prompt',
        'correct_answer',
        'explanation',
        'points',
    ];

    // Quan hệ: Một câu hỏi thuộc về một bài quiz
    public function quiz()
    {
        return $this->belongsTo(Quizzes::class);
    }

    // Quan hệ: Một câu hỏi có thể có nhiều câu trả lời của học viên
    public function answers()
    {
        return $this->hasMany(sentenceAnswers::class, 'question_id');
    }
}
