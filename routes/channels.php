<?php 
use Illuminate\Support\Facades\Broadcast;
Broadcast::channel('staff-support', function ($user) {
    return $user->role === 'staff';
});


?>  