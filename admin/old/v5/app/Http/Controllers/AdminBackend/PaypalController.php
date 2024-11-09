<?php

namespace App\Http\Controllers\AdminBackend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use Validator;
use URL;
use Session;
use Redirect;
use Input;
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

class PaypalController extends Controller
{
    private $_api_context;

    public function __construct()
    {
        $paypal_configuration = \Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential($paypal_configuration['client_id'], $paypal_configuration['secret']));
        $this->_api_context->setConfig($paypal_configuration['settings']);
    }

    public function index()
    {
        //return view('backend.pages.main.admin.home');

        $site_url = config('services.site.url');

        $payer = new Payer();
        $payer->setPaymentMethod("paypal");

        $currency = "USD";

        $price = 100;

        $itemName = "Service";

        // Set payment amount
        $amount = new Amount();
        $amount->setCurrency($currency)
            ->setTotal($price);

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

            //ResultPrinter::printError("Created Payment Using PayPal. Please visit the URL to Approve.", "Payment", null, $request, $ex);
            echo $ex;
            exit(1);
        }

        $approvalUrl = $payment->getApprovalLink();

        // ResultPrinter::printResult("Created Payment Using PayPal. Please visit the URL to Approve.", "Payment", "<a href='$approvalUrl' >$approvalUrl</a>", $request, $payment);

        $data['paymentID'] = $payment->id;

        echo json_encode($data);
    }


    public function process()
    {
    }
}
