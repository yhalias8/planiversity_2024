<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AgentPricingInquiryEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $subject;
    public $name;
    public $email;
    public $phone;
    public $country;
    public $state;
    public $message;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($subject, $name, $email, $phone, $country, $state, $message)
    {
        $this->subject = $subject;
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->country = $country;
        $this->state = $state;
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
