<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $table = 'schedules';
    protected $fillable = [
        'class_id',
        'day_of_week',
        'date',
        'start_time',
        'end_time',
        'teacher_id',
        'room_id',
        'status'
    ];

    protected $casts = [
    'date' => 'date',
    'start_time' => 'string',
    'end_time' => 'string',

];


    public function class()
    {
        return $this->belongsTo(classes::class, 'class_id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
