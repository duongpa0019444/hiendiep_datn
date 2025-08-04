<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationUser extends Model
{
    protected $table = 'notification_user';

    protected $fillable = [
        'notification_id',
        'user_id',
        'status',
        'previewed_at',
        'read_at',
        'created_at'
    ];

    public function notification()
    {
        return $this->belongsTo(notificationCoursePayments::class, 'notification_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
