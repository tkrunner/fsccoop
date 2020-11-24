<?php
    require_once("../config.inc.php");

    $data = [ 'status' => 0, 'responseText' => '' ];

    require_once("../token.validate.php");

    // $var_path = "https://system.nationco-op.org/";
	
	$var_path = "https://dev.policehospital-coop.com" ; 

    $year = isset($_POST['year']) ? $mysqli->real_escape_string($_POST['year']) : null;
    $month = isset($_POST['month']) ? $mysqli->real_escape_string($_POST['month']) : null;
    $inc_no = isset($_POST['inc_no']) ? $mysqli->real_escape_string($_POST['inc_no']) : null;
    // -- WHERE tb1.member_id = '{$member_id}' AND YEAR(tb2.receipt_datetime) = '{$year}' AND MONTH(tb2.receipt_datetime) = '{$month}' AND b1.receipt_id = '{$inc_no}'
    $yearThai = $year;
    $typeArr = array("ฝากเงิน", "ถอนเงิน", "ชำระเงินกู้");
    // $sql = "SELECT 
    // tb1.receipt_id,YEAR(tb2.receipt_datetime) + 543 AS years,MONTH(tb2.receipt_datetime) AS months,
    // CASE 
    //     WHEN tb1.account_list_id = 14 THEN 'หุ้น'
    //     ELSE IF(tb2.finance_month_profile_id IS NULL,'อื่น ๆ','รายเดือน')
    // END AS types
    // FROM coop_finance_transaction AS tb1
    // INNER JOIN coop_receipt AS tb2 ON tb1.receipt_id = tb2.receipt_id
    // WHERE tb1.member_id = '000001' AND YEAR(tb2.receipt_datetime) = 2020 AND MONTH(tb2.receipt_datetime) = '5' AND tb1.receipt_id = 'พ6303464'
    // GROUP BY tb1.receipt_id
    // ORDER BY MONTH(tb2.receipt_datetime) DESC";

        $data['status'] = 1;
        foreach($typeArr as $value){
            $data['data'][] = [
                'type' => $value,
            ];
        }

    echo json_encode($data); 
    exit();
?>
