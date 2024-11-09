<?php

namespace App\Listeners;

use App\Events\AgentPricingInquiryEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Twilio\Rest\Client;
use Twilio\Exceptions;

class SendPricingInquiryResponseSMSToAgentListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public $twilio;
    public $twilio_number;

    public function __construct()
    {

        $twilio_configuration = \Config::get('twilio');
        $this->twilio_number = $twilio_configuration['TWILIO_FROM_NUMBER'];
        $this->twilio = new Client($twilio_configuration['TWILIO_ACCOUNT_SID'], $twilio_configuration['TWILIO_AUTH_TOKEN']);
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\AgentPricingInquiryEvent  $event
     * @return void
     */
    public function handle(AgentPricingInquiryEvent $event)
    {

        $subject = $event->subject;
        $phone = "3023332679";
        $service_title = "";

        $user_name = $event->name;
        $user_email = $event->email;
        $user_phone = $event->phone;
        $user_country = $event->country;
        $user_state = $event->state;


        $message_lines = [
            "Hello",
            "We have received a new pricing inquery from a customer.",
            "Customer name : $user_name",
            "Customer email : $user_email",
            "Customer phone : $user_phone",
            "Country : $user_country",
            "",
            "planiversity",
        ];

        // Join the message lines with a newline character
        $body = implode("\n", $message_lines);

        $message = $this->twilio->messages
            ->create(
                $phone,
                [
                    "from" => $this->twilio_number,
                    "body" => $body
                ]
            );
    }
}
