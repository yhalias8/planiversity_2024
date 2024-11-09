<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SellerInquiryEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $service;
    public $subject;
    public $name;
    public $email;
    public $phone;
    public $message;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($service, $subject, $name, $email, $phone, $message)
    {
        $this->service = $service;
        $this->subject = $subject;
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->message = $message;
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
