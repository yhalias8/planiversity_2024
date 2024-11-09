	<?php
	include_once("config.ini.php");

	$fname = "John";
	$status = "Success";
	$amount = "50";

	$mail = new PHPMailer;	
	$mail->IsSMTP();
	//$mail->SMTPDebug = 1;
	$mail->SMTPAuth = true;
	$mail->SMTPSecure = 'ssl';
	$mail->Host = "planiversity.com";
	$mail->Port = 465; // or 587
	$mail->IsHTML(true);
	$mail->Username = "plans@planiversity.com";
	$mail->Password = "Oq7dQVMIda9-";

	$mail->setFrom($auth->config->site_email, $auth->config->site_name);
	$mail->addAddress('its.kraftbj@gmail.com', 'R');
	$mail->addReplyTo($auth->config->site_email, 'Support');
	$mail->isHTML(true);

	$mail->Subject = 'Planiversity.com - Card Payment';
	$mail->Body = 'Hello ' . $fname . ',<br/><br/> Thank you for your payment. <br /><br /> Payment Status: ' . $status . '<br />Payment Amount: $' . $amount;
	
	if(!$mail->send()) {
		echo "Opps! ";	
		echo "Mailer Error: " . $mail->ErrorInfo;
	} else {
		echo "Message has been sent";
	}