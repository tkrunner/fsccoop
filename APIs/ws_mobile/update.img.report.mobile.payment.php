<?php
    require "../config.inc.php";
	header("Content-type: application/json; charset=UTF-8");
    date_default_timezone_set('Asia/Bangkok');
    // define('UPLOAD_DIR', 'ws_mobile/img_report/');
    
    

     $imageBase64 = isset($_POST['imageGroup']) ? $mysqli->real_escape_string($_POST['imageGroup']) : null;
    $platform = isset($_POST['platform']) ? $mysqli->real_escape_string($_POST['platform']) : null;
    $member_no = isset($_POST['member_no']) ? $mysqli->real_escape_string($_POST['member_no']) : null;
    $mobile_uid = isset($_POST['mobile_uid']) ? $mysqli->real_escape_string($_POST['mobile_uid']) : null;
    $type_payment = isset($_POST['type_payment']) ? $mysqli->real_escape_string($_POST['type_payment']) : null;
    $ip = $_SERVER['REMOTE_ADDR'];
    $input_type1 = isset($_POST['input_type1']) ? $mysqli->real_escape_string($_POST['input_type1']) : null;
    $account_no = isset($_POST['account_no']) ? $mysqli->real_escape_string($_POST['account_no']) : null;
    $amount = isset($_POST['amount']) ? $mysqli->real_escape_string($_POST['amount']) : null;
    if($input_type1 == "ฝากเงิน"){
        $pay_type = 1;
    }else if($input_type1 == 'ถอนเงิน'){
        $pay_type = 3;
    }else{
        $pay_type = 2;
    }
    
    if($member_no != '' ){
        $sql = "INSERT INTO `cmp_sapp_payment`(
            `member_id`
            , `pay_type`
            , `pay_file`
            , `account_no`
            , `amount`
            , `createtime`
             , `is_confirm`)
        VALUES (
            '{$member_no}'
            , '{$pay_type}'
            , '{$imageBase64}'
            , '{$account_no}'
            , '{$amount}'
            , NOW()
            , 0 )" ;
$mysqli_app->query($sql);
echo $mysqli_app->error ;
        if($sql == true){
            $data['success'] = '1';
        }else{
            $data['success'] = '2';
        }
    }
    // https://www.policehospital-coop.com/uploads/app/payments/20200324200401nmwh6gA.png
            // $target_dir = "uploads/app/payments/";
            // $target_file = $target_dir . $_FILES["photo"]["name"];
            // $uploadOk = 1;
            // $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
            // $check = getimagesize($_FILES["photo"]["tmp_name"]);
            // $uploadOk = 1;
            // if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
            //     $data['success'] = '2';
            //     $data['sql'] = $sql;
            //     // echo "The file ". basename( $_FILES["photo"]["name"]). " has been uploaded.";
            // } else {
            //     $data['success'] = '1';
            //     $data['sql'] = $sql;
            // }
    echo json_encode($data);
    exit();

?>
