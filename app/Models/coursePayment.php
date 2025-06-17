<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class coursePayment extends Model
{
    //
    use SoftDeletes;
    protected $table = 'course_payments';

    protected $fillable = [
        'student_id',
        'class_id',
        'course_id',
        'amount',
        'status',
        'payment_code',
        'method',
        'note',
        'created_at',
        'updated_at',
    ];

    protected $with = ['user', 'course'];

    public function user()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function course()
    {
        return $this->belongsTo(courses::class, 'course_id');
    }

    public function class()
    {
        return $this->belongsTo(classes::class, 'class_id');
    }

    protected $casts = [
        'payment_date' => 'datetime',
    ];
}
