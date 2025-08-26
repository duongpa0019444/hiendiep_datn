<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class answers extends Model
{
    //
    protected $table = 'answers';

    protected $fillable = [
        'question_id',
        'content',
        'is_correct',
        'created_at',
        'updated_at'
    ];
}
