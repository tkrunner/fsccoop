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
    // $account_no = isset($_POST['account_no']) ? $mysqli->real_escape_string($_POST['account_no']) : null;
    $amount = isset($_POST['amount']) ? $mysqli->real_escape_string($_POST['amount']) : null;
    $account_id = isset($_POST['account_id']) ? $mysqli->real_escape_string($_POST['account_id']) : null;
    $amount_balance = 0.00;
    $account_deposit = isset($_POST['account_deposit']) ? $mysqli->real_escape_string($_POST['account_deposit']) : null;
    $check = isset($_POST['check']) ? $mysqli->real_escape_string($_POST['check']) : null;
    @$share_early = isset($_POST['share_early']) ? $mysqli->real_escape_string($_POST['share_early']) : null; 
    
    // SELECT transaction_balance FROM coop_account_transaction WHERE account_id =  1202327 ORDER BY transaction_time DESC LIMIT 0,1;
    if($check == 'checkaccount'){
        $sql = "SELECT transaction_balance FROM coop_account_transaction WHERE account_id =  '{$account_deposit}' ORDER BY transaction_time DESC LIMIT 0,1";
	 $rs = $mysqli->query($sql);
	 if ( $rs->num_rows ) {
        $row = $rs->fetch_assoc();
         $data['row'] = $row['transaction_balance'];
         if($row['transaction_balance'] == ''){
            $data['error'] ='ไม่พบบัญชีที่ท่านเลือก'; 
         }
     }else{
        $data['error'] ='ไม่พบบัญชีที่ท่านเลือก'; 
     }
    }else{
    $sql = "SELECT transaction_balance FROM coop_account_transaction WHERE account_id =  '{$account_id}' ORDER BY transaction_time DESC LIMIT 0,1";
	 $rs = $mysqli->query($sql);
	 if ( $rs->num_rows ) {
        $row = $rs->fetch_assoc();
        $amount_balance = $row['transaction_balance']-$amount;
     }
     if($check ==2){
        $sql = "INSERT INTO `coop_account_transaction`(
            `transaction_time`
            , `transaction_list`
            , `transaction_withdrawal`
            , `transaction_deposit`
            , `transaction_balance`
            , `user_id`
            , `account_id`
            , `balance_deposit`
            , `status_process`
             , `mobile_uid`)
        VALUES (
            NOW()
            ,'XWM'
            ,'{$amount}'
            , ''
            , '{$amount_balance}'
            , '{$member_no}'
            , '{$account_id}'
            , '{$balance_deposit}'
            , '{$platform}'
            , '{$mobile_uid}' )" ;

            $data['sql'] = $sql;
            $mysqli->query($sql);
            echo $mysqli->error ;
        if($sql == true){
            $data['update'] = '1 share';
        }else{
            $data['update'] = '2 share';
        }

     }else{
        //ถอนเงิน
        $sql = "INSERT INTO `coop_account_transaction`(
            `transaction_time`
            , `transaction_list`
            , `transaction_withdrawal`
            , `transaction_deposit`
            , `transaction_balance`
            , `user_id`
            , `account_id`
            , `balance_deposit`
            , `status_process`
             , `mobile_uid`)
        VALUES (
            NOW()
            ,'XWM'
            ,'{$amount}'
            , ''
            , '{$amount_balance}'
            , '{$member_no}'
            , '{$account_id}'
            , '{$balance_deposit}'
            , '{$platform}'
            , '{$mobile_uid}' )" ;

            $data['sql'] = $sql;
            $mysqli->query($sql);
            echo $mysqli->error ;
        if($sql == true){
            // ฝาก
            $data['success'] = '1';
            $sql = "SELECT transaction_balance FROM coop_account_transaction WHERE account_id =  '{$account_deposit}' ORDER BY transaction_time DESC LIMIT 0,1";
            $rs = $mysqli->query($sql);
            if ( $rs->num_rows ) {
               $row = $rs->fetch_assoc();
               $amount_balance = $row['transaction_balance']+$amount;
            }
            $sql1 = "INSERT INTO `coop_account_transaction`(
                `transaction_time`
                , `transaction_list`
                , `transaction_withdrawal`
                , `transaction_deposit`
                , `transaction_balance`
                , `user_id`
                , `account_id`
                , `balance_deposit`
                , `status_process`
                 , `mobile_uid`)
            VALUES (
                NOW()
                ,'XDM'
                ,''
                , '{$amount}'
                , '{$amount_balance}'
                , '{$member_no}'
                , '{$account_deposit}'
                , '{$balance_deposit}'
                , '{$platform}'
                , '{$mobile_uid}' )" ;
                $data['sql1'] = $sql1;
                $mysqli->query($sql1);
                echo $mysqli->error ;
            //ฝากเงิน
            $data['update'] = '1';
        }else{
            $data['success'] = '2';
            $data['update'] = '2';
        }
     }
    }
    
    echo json_encode($data);
    exit();

?>
