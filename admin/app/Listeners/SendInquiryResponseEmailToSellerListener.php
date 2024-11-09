<?php

namespace App\Listeners;

use App\Events\SellerInquiryEvent;
use App\Mail\InquiryEmail;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendInquiryResponseEmailToSellerListener
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
     * @param  \App\Events\SellerInquiryEvent  $event
     * @return void
     */

    public function handle(SellerInquiryEvent $event)
    {
        $current_timestamp = Carbon::now()->format('M d, Y h:i A');
        $service_data = $event->service;
        $subject = $event->subject;
        $user_name = $event->name;
        $user_email = $event->email;
        $user_phone = $event->phone;
        $user_message = $event->message;
        Mail::to($service_data->author_email)->send(new InquiryEmail($service_data->author_name, $service_data->service_title, $subject, $current_timestamp, $user_name, $user_email, $user_phone, $user_message));
    }
}
