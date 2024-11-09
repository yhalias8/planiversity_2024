<?php

namespace App\Listeners;

use App\Events\ServiceOrderPurchasedEvent;
use App\Mail\SendEmail;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendEmailToSellerListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\ServiceOrderPurchasedEvent  $event
     * @return void
     */

    public function handle(ServiceOrderPurchasedEvent $event)
    {
        $current_timestamp = Carbon::now()->format('M d, Y h:i A');
        $service_data = $event->service;
        $subject = $event->subject;
        $order_number = $event->order_number;
        $user_name = $event->username;
        $user_email = $event->useremail;
        $user_phone = $event->userphone;        
        Mail::to($service_data->author_email)->send(new SendEmail($service_data->author_name, $service_data->service_title, $subject, $order_number, $current_timestamp, $user_name, $user_email, $user_phone));
    }
}
