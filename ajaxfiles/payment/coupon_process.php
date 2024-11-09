<?php
include '../../config.ini.php';
include '../../config.ini.curl.php';

if (isset($_POST['code']) && isset($_POST['check']) && isset($_POST['date']) && isset($_POST['account_type'])) {

    $code = filter_var($_POST["code"], FILTER_SANITIZE_STRING);
    $account_type = filter_var($_POST["account_type"], FILTER_SANITIZE_STRING);
    
    $prefix = substr($code, 0, 3);
    $postfix = substr($code, -3);

    $stmt = $dbh->prepare("SELECT sha1(id) as id,CURDATE() as today_date,coupon_code,start_date,end_date,percent,lifetime,target_auth_level,target_plan_level FROM coupon WHERE coupon_code=? and status=? or id = (select id from `coupon` where coupon_prefix LIKE '%$prefix%' and coupon_postfix LIKE '%$postfix%' and bulk_coupon=1 and status='active')");    

    //$stmt = $dbh->prepare("SELECT sha1(id) as id,CURDATE() as today_date,coupon_code,start_date,end_date,percent,lifetime FROM coupon WHERE coupon_code=? and status=?");
    $stmt->bindValue(1, $code, PDO::PARAM_STR);
    $stmt->bindValue(2, 'active', PDO::PARAM_STR);
    $tmp = $stmt->execute();
    $aux = '';
    $list = [];

    if ($tmp && $stmt->rowCount() > 0) {
        $list = $stmt->fetch(PDO::FETCH_OBJ);

        $deltime1 = strtotime($list->start_date);
        $deltime2 = strtotime($list->end_date);
        $date = date('Y-m-d');
        $gettime = strtotime($list->today_date);

        $mdata = (object) array(
            "id" => $list->id,
            "coupon_code" => $list->coupon_code,
            "percent" => $list->percent,
            "today_date" => $list->today_date,
            "context" => $list->lifetime,
            "breakdown" => discountPercentProcess($list->percent),
            "plan_level" => $list->target_plan_level,            
        );
        
        $auth_level = array($userdata['account_type'], 'Either');

        if (($gettime >= $deltime1) && ($gettime <= $deltime2)) {


            if (in_array($list->target_auth_level, $auth_level)) {

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
                    'message' => "This code not associated with your account type ",
                );
            }


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
            'message' => "This coupon code does not exists",
        );
    }

    echo json_encode($response);
}

function discountPercentProcess($percent)
{

    $hold = 0;
    if ($percent == 100) {
        $hold = 1;
    }

    return $hold;
}
