<?php

namespace App\Http\Controllers\AdminBackend;

use App\Events\ServiceOrderPurchasedEvent;
use App\Events\SellerInquiryEvent;
use App\Http\Controllers\Controller;
use App\Mail\SendEmail;
use App\Models\MarketplaceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ExecutionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {

        $service_uuid = '7318b1d6-f9a2-4ea4-afdc-6525fc43098a';
        $serviceData = MarketplaceService::select('id', 'service_title', 'member_price', 'regular_price', 'sale_price', 'author_name', 'author_email', 'author_mobile')->where('service_uuid', $service_uuid)->first();
        
        //dd($serviceData);
        
        $subject = "New Order Received";
        $order_number = "777888";
        $hold = event(new ServiceOrderPurchasedEvent($serviceData, $subject, $order_number));
    

        echo "Executed Controller Mail";
    }
    
    public function run()
    {

        $service_uuid = '7318b1d6-f9a2-4ea4-afdc-6525fc43098a';
        $serviceData = MarketplaceService::select('id', 'service_title', 'author_name', 'author_email', 'author_mobile')->where('service_uuid', $service_uuid)->first();

        $subject = "New Inquiry Received";

        $name = "Mr coder";
        $email = "email@gmail.com";
        $phone = "123456789";
        $message = "new service inquery from a customer";

        event(new SellerInquiryEvent($serviceData, $subject, $name, $email, $phone, $message));

        echo "Run Controller Process";
    }
}
