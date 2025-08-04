<?php

namespace App\Events;

use App\Models\notificationCoursePayments;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;

class PaymentNotificationCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $notification;

    public function __construct(notificationCoursePayments $notification)
    {
        $this->notification = $notification;
    }

    public function broadcastOn()
    {
        return new Channel('admin-notifications');
    }

    public function broadcastWith()
    {
        return [
            'notification' => [
                'id' => $this->notification->id,
                'course_payment_id' => $this->notification->course_payment_id,
                'title' => $this->notification->title,
                'icon' => $this->notification->icon,
                'background' => $this->notification->background,
                'created_by' => $this->notification->created_by,
                'created_at' => $this->notification->created_at->format('d/m/Y H:i:s'),
            ]
        ];
    }
}
