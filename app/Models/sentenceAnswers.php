<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class sentenceAnswers extends Model
{
    //
    protected $table = 'sentence_answers';
    protected $fillable = [
        'attempt_id',
        'question_id',
        'user_answer',
        'is_correct',
        'created_at',
        'updated_at'
    ];
}
