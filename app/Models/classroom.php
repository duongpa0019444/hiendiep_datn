<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;
    protected $table = 'class_room';
    protected $fillable = [
        'room_name',
        'capacity',
        'status',
        'created_at',
        'updated_at',
        'note'
    ];

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'room');
    }

    // Quan hệ gián tiếp tới classes qua schedules
    public function classes()
    {
        return $this->hasManyThrough(classes::class, Schedule::class, 'room', 'id', 'id', 'class_id');
    }
}
