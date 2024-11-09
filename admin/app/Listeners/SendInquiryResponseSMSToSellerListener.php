<?php

namespace App\Listeners;

use App\Events\SellerInquiryEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Twilio\Rest\Client;
use Twilio\Exceptions;

class SendInquiryResponseSMSToSellerListener
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
     * @param  \App\Events\SellerInquiryEvent  $event
     * @return void
     */
    public function handle(SellerInquiryEvent $event)
    {
        $service_data = $event->service;
        $subject = $event->subject;
        $phone = $service_data->author_mobile;
        $service_title = $service_data->service_title;

        $user_name = $event->name;
        $user_email = $event->email;
        $user_phone = $event->phone;

        $message_lines = [
            "Hello $service_data->author_name,",
            "We have received a new service inquery from a customer.",
            "Service Name : $service_title",
            "Customer name : $user_name",
            "Customer email : $user_email",
            "Customer phone : $user_phone",
            "P.S. Don’t forget to check your email to read the message",
            "",
            "Planiversity",
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
