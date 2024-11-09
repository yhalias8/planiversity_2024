<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        \App\Events\ServiceOrderPurchasedEvent::class => [
            \App\Listeners\SendEmailToSellerListener::class,
            \App\Listeners\SendSMSToSellerListener::class,
        ],
        \App\Events\UserPurchasedEvent::class => [
            \App\Listeners\SendEmailToUserListener::class,
        ],        
        \App\Events\SellerInquiryEvent::class => [
            \App\Listeners\SendInquiryResponseEmailToSellerListener::class,
            \App\Listeners\SendInquiryResponseSMSToSellerListener::class,
        ],   
        \App\Events\AgentPricingInquiryEvent::class => [
            \App\Listeners\SendPricingInquiryResponseEmailToAgentListener::class,
            \App\Listeners\SendPricingInquiryResponseSMSToAgentListener::class,
        ],        
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
