<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
  require_once('config.php');

//die(print_r($_POST));
  $token  = $_POST['stripeToken'];
  $email  = $_POST['stripeEmail'];
  

  $customer = \Stripe\Customer::create([
      'email' => $email,
      'source'  => $token,
  ]);
  
//   die($customer->id);
$theAmount = $_POST['amnt'];

  
  $charge = \Stripe\Charge::create([
      'customer' => $customer->id,
      'amount'   => $theAmount,
      'currency' => 'usd',
  ]);

  echo '<h1>Successfully charged $'.$_POST['amnt'].'!</h1>';
?>