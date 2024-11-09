<?php

namespace App\Listeners;

use App\Events\AgentPricingInquiryEvent;
use App\Mail\PricingInquiryEmail;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendPricingInquiryResponseEmailToAgentListener
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
     * @param  \App\Events\AgentPricingInquiryEvent  $event
     * @return void
     */

    public function handle(AgentPricingInquiryEvent $event)
    {
        $current_timestamp = Carbon::now()->format('M d, Y h:i A');
        $agent_email = "planiversitymgmt@gmail.com";
        $subject = $event->subject;
        $user_name = $event->name;
        $user_email = $event->email;
        $user_phone = $event->phone;
        $user_country = $event->country;
        $user_state = $event->state;
        $user_message = $event->message;
        Mail::to($agent_email)->send(new PricingInquiryEmail($subject, $current_timestamp, $user_name, $user_email, $user_phone, $user_country, $user_state, $user_message));
    }
}
