<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class score extends Model
{

    protected $fillable = [
        'student_id',
        'class_id',
        'score_type',
        'score',
        'exam_date',
    ];

    public $timestamps = false;

    // Quan hệ với lớp học (courses)
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id', 'id');
    }



    // Quan hệ với giáo viên (users)
    public function class()
    {
        return $this->belongsTo(classes::class, 'class_id', 'id');
    }

    // Định dạng loại điểm
    public function scoreTypeVN()
    {
        return match ($this->score_type) {
            'midterm' => 'Giữa kỳ',
            'oral'    => 'Miệng',
            '15min'   => '15 phút',
            'final'   => 'Cuối kỳ',
            default   => $this->score_type,
        };
    }
}
