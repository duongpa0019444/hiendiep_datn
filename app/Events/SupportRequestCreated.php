<?php 

namespace App\Events;

use App\Models\SupportRequest;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

class SupportRequestCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets;

    public $support;

    public function __construct(SupportRequest $support)
    {
        $this->support = $support;
    }

    public function broadcastOn()
    {
        return new PresenceChannel('staff-support');
    }
}


?>