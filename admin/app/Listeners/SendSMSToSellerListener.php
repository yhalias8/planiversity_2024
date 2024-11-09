<?php

namespace App\Listeners;

use App\Events\ServiceOrderPurchasedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Twilio\Rest\Client;
use Twilio\Exceptions;

class SendSMSToSellerListener
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
     * @param  \App\Events\ServiceOrderPurchasedEvent  $event
     * @return void
     */
    public function handle(ServiceOrderPurchasedEvent $event)
    {
        $service_data = $event->service;
        $subject = $event->subject;
        $order_number = $event->order_number;
        $phone = $service_data->author_mobile;
        
        $user_name = $event->username;
        $user_email = $event->useremail;
        $user_phone = $event->userphone;        

        $message_lines = [
            "Hello $service_data->author_name,",
            "We are pleased to inform you that we have received a new order from a customer.",
            "Order Number : $order_number",
            "Customer name : $user_name",
            "Customer email : $user_email",
            "Customer phone : $user_phone",            
            "",
            "planiversity",
        ];

        // Join the message lines with a newline character
        $body = implode("\n", $message_lines);

       try {
           
        $message = $this->twilio->messages
            ->create(
                $phone,
                [
                    "from" => $this->twilio_number,
                    "body" => $body
                ]
            );
            
       } catch (TwilioException $e) {

            return $e;
      }
        

    }
}
