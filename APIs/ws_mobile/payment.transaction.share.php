<?php
    require "../config.inc.php";
	header("Content-type: application/json; charset=UTF-8");
    date_default_timezone_set('Asia/Bangkok');

     $imageBase64 = isset($_POST['imageGroup']) ? $mysqli->real_escape_string($_POST['imageGroup']) : null;
    $platform = isset($_POST['platform']) ? $mysqli->real_escape_string($_POST['platform']) : null;
    $member_no = isset($_POST['member_no']) ? $mysqli->real_escape_string($_POST['member_no']) : null;
    $mobile_uid = isset($_POST['mobile_uid']) ? $mysqli->real_escape_string($_POST['mobile_uid']) : null;
    $type_payment = isset($_POST['type_payment']) ? $mysqli->real_escape_string($_POST['type_payment']) : null;
    $ip = $_SERVER['REMOTE_ADDR'];
    $input_type1 = isset($_POST['input_type1']) ? $mysqli->real_escape_string($_POST['input_type1']) : null;
    $share_early = isset($_POST['share_early']) ? $mysqli->real_escape_string($_POST['share_early']) : null;
    $account_id = isset($_POST['account_id']) ? $mysqli->real_escape_string($_POST['account_id']) : null;
    $share_value = 0.00;
    $share_payable_value = 0.00;
    $share_collect = 0.00;
    $share_collect_value = 0.00;
    $account_deposit = isset($_POST['account_deposit']) ? $mysqli->real_escape_string($_POST['account_deposit']) : null;
    
    $sql = "SELECT share_payable,share_payable_value,share_collect,share_collect_value,share_bill FROM coop_mem_share WHERE member_id =  '{$member_no}' ORDER BY share_date DESC LIMIT 0,1";
	 $rs = $mysqli->query($sql);
	 if ( $rs->num_rows ) {
        $row = $rs->fetch_assoc();
        $share_payable = $row['share_payable'];
        $share_collect_old = $row['share_collect'];
        $share_collect_value_old = $row['share_collect_value'];
        $share_bill = $row['share_bill'];
     }
     $sql = "SELECT setting_value FROM coop_share_setting";
	 $rs = $mysqli->query($sql);
	 if ( $rs->num_rows ) {
        $row = $rs->fetch_assoc();
        $share_value = $row['setting_value'];
        $share_early_value = $share_early*$row['setting_value'];
        $share_payable_value = $share_payable*$share_value;
     }
        $share_collect = $share_collect_old+$share_early;
        $share_collect_value = $share_collect_value_old+$share_early_value;

    //ถอนเงิน
    $sql2 = "INSERT INTO `coop_mem_share`(
        `member_id`
        , `admin_id`
        , `share_type`
        , `share_date`
        , `share_status`
        , `share_payable`
        , `share_payable_value`
        , `share_early`
        , `share_early_value`
        , `share_collect`
        , `share_collect_value`
        , `share_bill`
        , `share_bill_date`
        , `share_value`
        , `share_id`
         , `pay_type`)
    VALUES (
        '{$member_no}'
        ,'CNV'
        , 'SPA'
        , NOW()
        , '1'
        , '{$share_collect_old}'
        , '{$share_collect_value_old}'
        , '{$share_early}'
        , '{$share_early_value}'
        , '{$share_collect}'
        , '{$share_collect_value}'
        , ''
        , NOW()
        , '{$share_value}'
        , ''
        , '1' )" ;

        $data['sql2'] = $sql2;
        $rs = $mysqli->query($sql2);
        echo $mysqli->error ;
    if($sql2 == true){
        $data['success'] = '1';
        $data['update'] = '1';
        
        $sql = "SELECT share_id,share_date,share_early_value FROM coop_mem_share WHERE member_id =  '{$member_no}' ORDER BY share_date DESC LIMIT 0,1";
        $rs = $mysqli->query($sql);
        $row = $rs->fetch_assoc();
        $share_id = $row['share_id'];
                $yymm = (date("Y", strtotime($row["share_date"]))+543).date("m", strtotime($row["share_date"]));
                $data['$yymmCheck'] = $yymm;
                $data['$yymmCheckshare_date'] = $row["share_date"];
                $data['$share_bill'] = $share_bill;
                $share_early_value = $row["share_early_value"];

                if($share_bill != ''){
                    $sql = "SELECT * FROM coop_receipt WHERE receipt_id LIKE '".$yymm."%' ORDER BY receipt_id DESC LIMIT 0,1";
                    $rs = $mysqli->query($sql);
                    if ( $rs->num_rows ) {
                        $row1 = $rs->fetch_assoc();
                        $id = (int) substr($row1["receipt_id"], 6);
                        $receipt_number = $yymm.sprintf("%06d", $id + 1);
                   }
                }else{
                    $data['check'] = '1';
                    $receipt_number = $yymm."000001";
                    $data['$yymm'] = $yymm;
                }

           $sql3 = "UPDATE coop_mem_share SET share_bill = '{$receipt_number}', share_bill_date = NOW() WHERE share_id = '{$share_id}';";
            $data['sql3'] = $sql3;
            $mysqli->query($sql3);

                $sql4 = "INSERT INTO `coop_receipt`(
                `receipt_id`
                , `member_id`
                , `sumcount`
                , `admin_id`
                , `receipt_datetime`
                , `receipt_status`
                 , `pay_type`)
            VALUES (
                '{$receipt_number}'
                ,'{$member_no}'
                , '{$share_early_value}'
                , ''
                , '{$row["share_date"]}'
                , '0'
                , '1' )" ;
        
                $data['sql4'] = $sql4;
                $rs = $mysqli->query($sql4);

                $sql5 = "INSERT INTO `coop_receipt_detail`(
                    `receipt_id`
                    , `receipt_list`
                    , `receipt_count`
                     , `receipt_count_item`)
                VALUES (
                    '{$receipt_number}'
                    ,'14'
                    , '{$share_early_value}'
                    , '{$share_early}' )" ;
            
                    $data['sql5'] = $sql5;
                    $rs = $mysqli->query($sql5);

                    $sql6 = "INSERT INTO `coop_finance_transaction`(
                        `member_id`
                        , `receipt_id`
                        , `account_list_id`
                        , `principal_payment`
                        , `interest`
                        , `transaction_text`
                        , `deduct_type`
                        , `total_amount`
                        , `payment_date`
                         , `createdatetime`)
                    VALUES (
                        '{$member_no}'
                        ,'{$receipt_number}'
                        , '14'
                        ,'{$share_early_value}'
                        ,''
                        , 'หุ้น'
                        ,'all'
                        ,'{$share_early_value}'
                        , '{$row["share_date"]}'
                        , NOW() )" ;
                
                        $data['sql6'] = $sql6;
                        $rs = $mysqli->query($sql6);
            //     echo $mysqli->error ;
        
    }else{
        $data['success'] = '2';
        $data['update'] = '2';
    }

    // function insert_receipt(){
    //     $sql4 = "INSERT INTO `coop_receipt`(
    //             `receipt_id`
    //             , `member_id`
    //             , `sumcount`
    //             , `admin_id`
    //             , `receipt_datetime`
    //             , `receipt_status`
    //              , `pay_type`)
    //         VALUES (
    //             '{$receipt_number}'
    //             ,'{$member_no}'
    //             , '{$share_early_value}'
    //             , ''
    //             , '1'
    //             , '{$row["share_date"]}'
    //             , '0'
    //             , '1' )" ;
        
    //             $data['sql4'] = $sql4;
    //             $rs = $mysqli->query($sql4);
    //             echo $mysqli->error ;
    // }
    echo json_encode($data);
    exit();


   
?>
