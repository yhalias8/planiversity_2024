<?php

namespace App\Listeners;

use App\Events\UserPurchasedEvent;
use App\Mail\UserOrderEmail;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendEmailToUserListener
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
     * @param  \App\Events\UserPurchasedEvent  $event
     * @return void
     */
    public function handle(UserPurchasedEvent $event)
    {
        $current_timestamp = Carbon::now()->format('M d, Y h:i A');
        $service_data = $event->service;
        $subject = $event->subject;
        $order_number = $event->order_number;
        $username = $event->username;
        $useremail = $event->useremail;
        Mail::to($useremail)->send(new UserOrderEmail($service_data->author_name, $service_data->service_title, $subject, $order_number, $username, $current_timestamp));
    }
}
