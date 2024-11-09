<?php

use PayPal\Api\ChargeModel;
use PayPal\Api\Currency;
use PayPal\Api\MerchantPreferences;
use PayPal\Api\PaymentDefinition;
use PayPal\Api\Plan;
use PayPal\Api\Patch;
use PayPal\Api\PatchRequest;
use PayPal\Common\PayPalModel;

// Create a new billing plan
if (!empty($_POST["plan_name"]) && !empty($_POST["plan_description"])) {

    $plan = new Plan();
    $plan->setName($_POST["plan_name"])
        ->setDescription($_POST["plan_description"])
        ->setType('FIXED');

    // Set billing plan definitions
    $paymentDefinition = new PaymentDefinition();
    $paymentDefinition->setName('Regular Payments')
        ->setType('REGULAR')
        ->setFrequency('DAY')
        ->setFrequencyInterval('1')
        ->setCycles('1')
        ->setAmount(new Currency(array(
            'value' => 24.99,
            'currency' => 'USD'
        )));

    // Set charge models
    $chargeModel = new ChargeModel();
    $chargeModel->setType('SHIPPING')->setAmount(new Currency(array(
        'value' => 24.99,
        'currency' => 'USD'
    )));
    $paymentDefinition->setChargeModels(array(
        $chargeModel
    ));

    // Set merchant preferences
    $merchantPreferences = new MerchantPreferences();
    $merchantPreferences->setReturnUrl('https://planiversity.com/billing_new.php?status=success')
        ->setCancelUrl('https://planiversity.com/billing_new.php?status=cancel')
        ->setAutoBillAmount('yes')
        ->setInitialFailAmountAction('CONTINUE')
        ->setMaxFailAttempts('0')
        ->setSetupFee(new Currency(array(
            'value' => 24.99,
            'currency' => 'USD'
        )));

    $plan->setPaymentDefinitions(array(
        $paymentDefinition
    ));
    $plan->setMerchantPreferences($merchantPreferences);

    try {
        $createdPlan = $plan->create($apiContext);
        echo "asdf";
        try {
            $patch = new Patch();
            $value = new PayPalModel('{"state":"ACTIVE"}');
            $patch->setOp('replace')
                ->setPath('/')
                ->setValue($value);
            $patchRequest = new PatchRequest();
            $patchRequest->addPatch($patch);
            $createdPlan->update($patchRequest, $apiContext);
            $patchedPlan = Plan::get($createdPlan->getId(), $apiContext);

            require_once "billing_paypal_subscription_agreement.php";
        } catch (PayPal\Exception\PayPalConnectionException $ex) {
            echo $ex->getCode();
            echo $ex->getData();
            die($ex);
        } catch (Exception $ex) {
            die($ex);
        }
    } catch (PayPal\Exception\PayPalConnectionException $ex) {
        echo $ex->getCode();
        echo $ex->getData();
        die($ex);
    } catch (Exception $ex) {
        die($ex);
    }
}
