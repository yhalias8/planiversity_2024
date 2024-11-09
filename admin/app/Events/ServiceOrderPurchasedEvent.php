<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ServiceOrderPurchasedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $service;
    public $subject;
    public $order_number;
    public $username;
    public $useremail;
    public $userphone;    

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($service, $subject, $order_number, $username, $useremail, $userphone)
    {
        $this->service = $service;
        $this->subject = $subject;
        $this->order_number = $order_number;
        $this->username = $username;
        $this->useremail = $useremail;
        $this->userphone = $userphone;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
