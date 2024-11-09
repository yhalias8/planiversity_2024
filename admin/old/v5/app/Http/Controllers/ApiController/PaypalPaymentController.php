<?php

namespace App\Http\Controllers\ApiController;

use App\Http\Controllers\Controller;
use App\Models\MarketplaceOrder;
use App\Models\MarketplaceService;
use App\Models\MarketplaceOrderPayment;
use App\Models\UserList;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Exception;

use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\WebProfile;
use PayPal\Api\InputFields;
use PayPal\Api\ExecutePayment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;


class PaypalPaymentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private $_api_context;

    public function __construct()
    {

        $paypal_configuration = \Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential($paypal_configuration['client_id'], $paypal_configuration['secret']));
        $this->_api_context->setConfig($paypal_configuration['settings']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function instance_create(Request $request)
    {

        $data = $request->json()->all();

        $rules = [
            'token' => 'required',
            'service_uuid' => 'required',
        ];


        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->all(),
                'status' => JsonResponse::HTTP_BAD_REQUEST,
            ], JsonResponse::HTTP_BAD_REQUEST);
            return;
        }

        //echo $data['service_uuid'];

        $site_url = config('services.site.url');

        $guest = 1;
        $member = 0;
        $user_id = null;


        if (isset($data['u_number']) && !empty($data['u_number'])) {
            $singleUser = UserList::where('customer_number', $data['u_number'])->first();

            $guest = 0;
            $member = 1;
            $user_id = $singleUser->id;
        }

        $serviceData = MarketplaceService::select('id', 'service_title', 'member_price', 'regular_price', 'sale_price')->where('service_uuid', $data['service_uuid'])->first();
        $itemPrice = MarketplaceService::price_calculation($serviceData, $member);


        $payer = new Payer();
        $payer->setPaymentMethod("paypal");

        $currency = "USD";

        $itemPrice = number_format($itemPrice, 2);

        $itemName = "Planiversity Service - " . $serviceData['service_title'];

        // Set payment amount
        $amount = new Amount();
        $amount->setCurrency($currency)
            ->setTotal($itemPrice);

        // Set transaction object
        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setDescription("$itemName")
            ->setInvoiceNumber(uniqid());

        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl($site_url . "?process=succes&id=" . uniqid())
            ->setCancelUrl($site_url . "?process=failed");

        // Add NO SHIPPING OPTION
        $inputFields = new InputFields();
        $inputFields->setNoShipping(1);

        $webProfile = new WebProfile();
        $webProfile->setName('test' . uniqid())->setInputFields($inputFields);

        $webProfileId = $webProfile->create($this->_api_context)->getId();

        $payment = new Payment();
        $payment->setExperienceProfileId($webProfileId); // no shipping
        $payment->setIntent("sale")
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions(array($transaction));

        $request = clone $payment;

        try {
            $payment->create($this->_api_context);
        } catch (\PayPal\Exception\PPConnectionException $ex) {

            return response()->json([
                'message' => $ex,
                'status' => JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        $approvalUrl = $payment->getApprovalLink();

        // ResultPrinter::printResult("Created Payment Using PayPal. Please visit the URL to Approve.", "Payment", "<a href='$approvalUrl' >$approvalUrl</a>", $request, $payment);

        //$data['paymentID'] = $payment->id;

        //echo json_encode($data);


        // $d['paymentID'] = $payment->id;

        // echo json_encode($d);

        return response()->json([
            'paymentID' => $payment->id,
            'message' => 'Payment instance created successfully',
            'status' => JsonResponse::HTTP_OK,
        ], JsonResponse::HTTP_OK);
    }


    public function instance_execute(Request $request)
    {

        $data = $request->json()->all();

        $rules = [
            'token' => 'required',
            'service_uuid' => 'required',
            'payment_id' => 'required',
            'payer_id' => 'required',
        ];

        $validator = Validator::make($data, $rules);

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

        $serviceData = MarketplaceService::select('id', 'service_title', 'member_price', 'regular_price', 'sale_price')->where('service_uuid', $data['service_uuid'])->first();
        $itemPrice = MarketplaceService::price_calculation($serviceData, $member);


        if ($serviceData) {
            $order = $this->orderCreation($serviceData, $itemPrice, $user_id, $guest, $data['uuid']);
        }

        $payment = Payment::get($data['payment_id'], $this->_api_context);

        $execution = new PaymentExecution();
        // $execution->setPayerId($request->payerID);
        $execution->setPayerId($data['payer_id']);

        try {
            $result = $payment->execute($execution, $this->_api_context);
        } catch (PayPal\Exception\PayPalConnectionException $ex) {
            return response()->json([
                'message' => $ex,
                'status' => JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }


        $decode_result = json_decode($result);

        if ($result->state == 'approved' && empty($result->failed_transactions)) {

            $amount = $decode_result->transactions[0]->amount->total;
            $currency = $decode_result->transactions[0]->amount->currency;
            $status = $decode_result->transactions[0]->related_resources[0]->sale->state;


            $order_payment = new MarketplaceOrderPayment();
            //$order_payment->id_user = $user_id;
            $order_payment->transaction_id = $payment->getTransactions()[0]->getRelatedResources()[0]->getSale()->getId();
            $order_payment->order_id = $order->id;
            $order_payment->fname = null;
            $order_payment->lname = null;
            $order_payment->address = null;
            $order_payment->city = null;
            $order_payment->state = null;
            $order_payment->zipcode = null;
            $order_payment->plan_type = "service";
            $order_payment->payment_type = "paypal";
            $order_payment->amount = $amount;
            $order_payment->ip_address =  $request->ip();
            $order_payment->status = $status;
            $saved = $order_payment->save();

            if ($saved) {
                $order_update = MarketplaceOrder::findorfail($order->id);
                $order_update->status = $this->statusCalculation($status);
                $updated = $order_update->save();

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
