<?php

namespace App\Http\Controllers\ApiController;

use App\Events\ServiceOrderPurchasedEvent;
use App\Events\UserPurchasedEvent;
use App\Http\Controllers\Controller;
use App\Models\MarketplaceOrder;
use App\Models\MarketplaceService;
use App\Models\MarketplaceWishList;
use App\Models\MarketplaceOrderPayment;
use App\Models\UserList;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Exception;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Charge;


class StripePaymentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
        $stripe_configuration = \Config::get('stripe');
        Stripe::setApiKey($stripe_configuration['SECRET_KEY']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function store(Request $request)
    {

        $data = $request->json()->all();

        $rules = [
            'fname' => 'required',
            'lname' => 'required',
            'email' => 'required|email',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'stripeToken' => 'required',
            'service_uuid' => 'required',
        ];

        $customMessages = [
            'fname.required' => 'The first name field is required',
            'lname.required' => 'The last name field is required',
        ];

        $validator = Validator::make($data, $rules, $customMessages);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->all(),
                'status' => JsonResponse::HTTP_BAD_REQUEST,
            ], JsonResponse::HTTP_BAD_REQUEST);
            return;
        }

        $guest = 1;
        $member = 0;
        $user_id = null;

        if (isset($data['u_number']) && !empty($data['u_number'])) {
            $singleUser = UserList::where('customer_number', $data['u_number'])->first();

            $guest = 0;
            $member = 1;
            $user_id = $singleUser->id;
        }

        $serviceData = MarketplaceService::select('id', 'service_title', 'member_price', 'regular_price', 'sale_price', 'author_name', 'author_email', 'author_mobile')->where('service_uuid', $data['service_uuid'])->first();
        $itemPrice = MarketplaceService::price_calculation($serviceData, $member);

        if ($serviceData) {
            $order = $this->orderCreation($serviceData, $itemPrice, $user_id, $guest, $data['uuid']);
        }

        try {
            $customer = Customer::create(array(
                'email' => $data['email'],
                'source'  => $data['stripeToken']
            ));
        } catch (Exception $e) {
            $message = $e->getMessage();

            return response()->json([
                'message' => $message,
                'status' => JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        $itemName = "Planiversity Service - " . $serviceData['service_title'];

        $itemPrice = $itemPrice * 100;
        $currency = "USD";
        $chargeJson = [];

        try {
            //charge a credit or a debit card
            $charge = Charge::create(array(
                'customer' => $customer->id,
                'amount'   => $itemPrice,
                'currency' => $currency,
                'description' => $itemName,
                'metadata' => array(
                    'uuid' => $data['uuid'],
                    'order_number' => $order->order_number,
                    'service_uuid' => $data['service_uuid']
                )
            ));
        } catch (Exception $e) {
            $message = $e->getMessage();
            return response()->json([
                'message' => $message,
                'status' => JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        $chargeJson = $charge->jsonSerialize();

        if ($chargeJson['amount_refunded'] == 0 && empty($chargeJson['failure_code']) && $chargeJson['paid'] == 1 && $chargeJson['captured'] == 1) {

            $status = $chargeJson['status'];

            $order_payment = new MarketplaceOrderPayment();
            $order_payment->transaction_id = $chargeJson['balance_transaction'];
            $order_payment->order_id = $order->id;
            $order_payment->fname = $data['fname'];
            $order_payment->lname = $data['lname'];
            $order_payment->email = $data['email'];
            $order_payment->phone_number = $data['phone_number'];            
            $order_payment->address = $data['address'];
            $order_payment->city = $data['city'];
            $order_payment->state = $data['state'];
            $order_payment->zipcode = $data['zip'];
            $order_payment->plan_type = "service";
            $order_payment->payment_type = "stripe";
            $order_payment->amount = $chargeJson['amount'] / 100;
            $order_payment->ip_address =  $request->ip();
            $order_payment->status = $status;
            $saved = $order_payment->save();

            if ($saved) {

                $order_update = MarketplaceOrder::findorfail($order->id);
                $order_update->status = $this->statusCalculation($status);
                $updated = $order_update->save();
                
                $subject = "New Order Received";
                event(new ServiceOrderPurchasedEvent($serviceData, $subject, $order->order_number, $data['fname'] . " " . $data['lname'], $data['email'], $data['phone_number']));
                $subject = "Thank you for your recent order";
                event(new UserPurchasedEvent($serviceData, $subject, $order->order_number, $data['fname'] . " " . $data['lname'], $data['email']));                

                return response()->json([
                    'order_number' => $order->order_number,
                    'message' => 'Payment has been made successfully',
                    'status' => JsonResponse::HTTP_OK,
                ], JsonResponse::HTTP_OK);
            } else {
                return response()->json([
                    'message' => 'Data saved failed',
                    'status' => JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
                ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
            }
        } else {

            return response()->json([
                'message' => 'Transaction has been failed',
                'status' => JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    private function orderCreation($service, $price, $user_id, $guest, $uuid)
    {

        $order = new MarketplaceOrder();
        $order->order_number = $this->generateUniqueCode();
        $order->service_id = $service->id;
        $order->service_price = $price;
        $order->uuid = $uuid;
        $order->user_id = $user_id;
        $order->guest = $guest;
        $order->status = "pending";
        $saved = $order->save();
        $order_number = $order;
        return $order_number;
    }

    // private function orderNumberCalculation()
    // {
    //     $latestOrder = MarketplaceOrder::orderBy('created_at', 'DESC')->first();
    //     $order_number = 'O-' . str_pad($latestOrder->id + 1, 8, "0", STR_PAD_LEFT);
    //     return $order_number;
    // }


    private function generateUniqueCode()
    {
        do {
            $referal_code = random_int(100000, 999999);
        } while (MarketplaceOrder::where("id", "=", $referal_code)->first());

        return $referal_code;
    }

    private function statusCalculation($status)
    {
        $default = "completed";
        if ($status != "succeeded") {
            $default = $status;
        }
        return $default;
    }
}
