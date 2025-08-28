<?php

namespace App\Models;

use App\Models\User as ModelsUser;
use Illuminate\Database\Eloquent\Model;

class notificationCoursePayments extends Model
{
    public $timestamps = true;
    protected $table = 'notification_course_payments';

    protected $fillable = [
        'title',
        'course_payment_id',
        'icon',
        'background',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by'
    ];

    public function coursePayment()
    {
        return $this->belongsTo(coursePayment::class, 'course_payment_id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(ModelsUser::class, 'updated_by');
    }
}
