<?php
    header("Content-type: application/json; charset=UTF-8");
    date_default_timezone_set('Asia/Bangkok');
    require_once("../config.inc.php");

    $data = [ 'status' => 0, 'responseText' => '' ];
    $pay_type = array( 1 => "เงินฝาก " , "เงินกู้" , "ถอนเงิน") ;
    $sql = "SELECT cmp_sapp_payment.* , `cmp_sapp_member`.`name` ,`cmp_sapp_member`.`mobile` FROM `cmp_sapp_payment` 
    LEFT JOIN `cmp_sapp_member` 
    ON `cmp_sapp_payment`.`member_id` =  `cmp_sapp_member`.`member_id` WHERE `cmp_sapp_payment`.`member_id` IS NOT NULL 
    ORDER BY `cmp_sapp_payment`.`createtime` DESC";

    $rs = $mysqli_app->query($sql);

    if ( $rs->num_rows ) {
        $sum = 0.00;
        $data['status'] = 1;
        // this.deposit_sum = this.func.formatNumber(this.data.deposit_sum.toFixed(2))
        while( ($row = $rs->fetch_assoc()) ){
            $data['data'][] = [
                'pay_id' => $row["pay_id"],
                'createtime' =>  dateDB2thaidate($row['createtime'],true,false),
                'member_id' => $row["member_id"],
                'mobile' =>  $row["mobile"],
                'name' =>  $pay_type[$row["pay_type"]],
                'account_no' => $row["account_no"],
                'pay_type' =>  $row["pay_type"] == 1 || $row["pay_type"] == 2 ? number_format($row["amount"] , 2 , "." , "," )  : "-",
                'pay_file' => $row["pay_file"],
                'is_confirm' => $row["is_confirm"] == 0  ? 'รอตรวจสอบ' : "ตรวจสอบแล้ว",
            ];
        }
    } else {
        $data['responseText'] = 'ไม่พบหลักฐานการชำระเงิน';
    }

    echo json_encode($data); 
    exit();
?>