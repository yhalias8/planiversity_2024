<?php
require_once('vendor/stripe/stripe-php/init.php');

$stripe = [
   "secret_key"      => "sk_test_D393YOh4kjIn13SQNFaRAhaU",
//"secret_key"      => "sk_test_oKvUrVpbN31qQPs9T4KwH9XQ",
   "publishable_key" => "pk_test_QNdtnTkbtazXoLwNCm1zZtqP",
//"publishable_key" => "pk_test_ziATmfXWa7k9OQ1D5itJmgE0",
];

\Stripe\Stripe::setApiKey($stripe['secret_key']);