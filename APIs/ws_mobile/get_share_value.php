<?php
    require "../config.inc.php";
	header("Content-type: application/json; charset=UTF-8");
    date_default_timezone_set('Asia/Bangkok');
    // define('UPLOAD_DIR', 'ws_mobile/img_report/');
    $member_no = isset($_POST['member_no']) ? $mysqli->real_escape_string($_POST['member_no']) : null;
    $index = isset($_POST['check']) ? $mysqli->real_escape_string($_POST['check']) : null;
    $account_deposit = isset($_POST['account_id']) ? $mysqli->real_escape_string($_POST['account_id']) : null;
    

     $setting_value = 0;
    
    // SELECT transaction_balance FROM coop_account_transaction WHERE account_id =  1202327 ORDER BY transaction_time DESC LIMIT 0,1;
    if($index == 2){
        $sql = "SELECT setting_value FROM coop_share_setting";
        $rs = $mysqli->query($sql);
        if ( $rs->num_rows ) {
           $row = $rs->fetch_assoc();
           $data['setting_value'] = $row['setting_value'];
        }
   
        $sql = "SELECT share_payable,share_payable_value,share_collect,share_collect_value,share_bill FROM coop_mem_share WHERE member_id =  '{$member_no}' ORDER BY share_date DESC LIMIT 0,1";
        $rs = $mysqli->query($sql);
        if ( $rs->num_rows ) {
           $row = $rs->fetch_assoc();
           $data['share_payable'] = $row['share_payable'];
           $data['share_payable_value'] = $row['share_payable_value'];
           $data['share_collect'] = $row['share_collect'];
           $data['share_collect_value'] = $row['share_collect_value'];
           $data['share_bill'] = $row['share_bill'];
        //    $data
        }   
    }else{
        $sql = "SELECT transaction_id FROM coop_account_transaction WHERE account_id =  '{$account_deposit}' AND transaction_list = 'XWM' ORDER BY transaction_time DESC LIMIT 0,1";
	 $rs = $mysqli->query($sql);
	 if ( $rs->num_rows ) {
        $row = $rs->fetch_assoc();
        $data['transaction_id'] = $row['transaction_id'];
	 }
     
    }
   
     
   
    echo json_encode($data);
    exit();

?>
