<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class lessions extends Model
{
    //
    protected $table = 'lessons';

    protected $fillable = ['name', 'link_document', 'quizzes_id', 'course_id ', 'created_at','updated_at'];

    // Quan hệ với bảng course_payment
   
    public function lessions(){
        return self::all();

    }
    public function getCourseById($id)
    {
        return self::find($id);
    }
}
