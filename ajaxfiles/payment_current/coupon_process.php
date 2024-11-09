<?php
include '../../config.ini.php';
include '../../config.ini.curl.php';

if (isset($_POST['code']) && isset($_POST['check']) && isset($_POST['date']) && isset($_POST['account_type']) ) {

    $code = filter_var($_POST["code"], FILTER_SANITIZE_STRING);
    $account_type = filter_var($_POST["account_type"], FILTER_SANITIZE_STRING);

    $stmt = $dbh->prepare("SELECT sha1(id) as id,CURDATE() as today_date,coupon_code,start_date,end_date,percent,paypal_individual_plan_id,paypal_business_plan_id FROM coupon WHERE coupon_code=? and status=?");
    $stmt->bindValue(1, $code, PDO::PARAM_STR);
    $stmt->bindValue(2, 'active', PDO::PARAM_STR);
    $tmp = $stmt->execute();
    $aux = '';
    $list = [];

    if ($tmp && $stmt->rowCount() > 0) {
        $list = $stmt->fetch(PDO::FETCH_OBJ);
        //echo json_encode($list);


        $deltime1 = strtotime($list->start_date);
        $deltime2 = strtotime($list->end_date);
        $date = date('Y-m-d');
        $gettime = strtotime($list->today_date);

            if ($account_type == 'individual') {
                $plan = $list->paypal_individual_plan_id;
            } else {
                $plan = $list->paypal_business_plan_id;
            }

        $mdata = (object) array(
            "id" => $list->id,
            "coupon_code" => $list->coupon_code,
            "percent" => $list->percent,
            "today_date" => $list->today_date,
            "plan" => $plan
        );

        if (($gettime >= $deltime1) && ($gettime <= $deltime2)) {

            http_response_code(200);

            $response = array(
                "status" => 200,
                "code" => $code,
                "data" => $mdata,
                "message" => "Successfully coupon code fetched",
            );
        } else {

            http_response_code(422);

            $response = array(
                'status' => 422,
                "code" => $code,
                'message' => "This coupon code has expired",
            );
        }
    } else {

        http_response_code(422);
        $response = array(
            'status' => 422,
            "code" => $code,
            'message' => "Coupon code does not exists",
        );
    }

    echo json_encode($response);
}
