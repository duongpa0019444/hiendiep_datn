<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class notificationCoursePayments extends Model
{
    protected $table = 'notification_course_payments';

    protected $fillable = [
        'course_payment_id',
        'user_id',
        'status',
        'previewed_at',
        'read_at',
        'created_at',
        'updated_at'
    ];

    public function coursePayment()
    {
        return $this->belongsTo(coursePayment::class, 'course_payment_id');
    }
}
