<?php
     header("Content-type: application/json; charset=UTF-8");
    //  header('content-type: application/json;charset=utf-8');
     header("Access-Control-Allow-Origin: *");
     header('Access-Control-Allow-Headers: X-Requested-With, content-type, access-control-allow-origin, access-control-allow-methods, access-control-allow-headers');
     header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
     date_default_timezone_set('Asia/Bangkok');
     require_once("../config.inc.php");
     // require PATH."/class/function.inc.php";
 
     
     $member_no = isset($_POST['member_no']) ? $mysqli->real_escape_string($_POST['member_no']) : null;
     $account_id = isset($_POST['account_id']) ? $mysqli->real_escape_string($_POST['account_id']) : null;
     $account_id2 = isset($_POST['account_id2']) ? $mysqli->real_escape_string($_POST['account_id2']) : null;
     $account_deposit = isset($_POST['account_deposit']) ? $mysqli->real_escape_string($_POST['account_deposit']) : null;
     $mobile_uid = isset($_POST['mobile_uid']) ? $mysqli->real_escape_string($_POST['mobile_uid']) : null;
     $amount = isset($_POST['amount']) ? $mysqli->real_escape_string($_POST['amount']) : null;
     $accountName2 = isset($_POST['accountName2 ']) ? $mysqli->real_escape_string($_POST['accountName2 ']) : null;
     $accountName = isset($_POST['accountName ']) ? $mysqli->real_escape_string($_POST['accountName ']) : null;
     $balance = isset($_POST['balance']) ? $mysqli->real_escape_string($_POST['balance']) : null;
     $fileName =isset($_POST['fileName']) ? $mysqli->real_escape_string($_POST['fileName']) : null; 
     $status_payment =isset($_POST['status_payment']) ? $mysqli->real_escape_string($_POST['status_payment']) : null; 
     $index =isset($_POST['index']) ? $mysqli->real_escape_string($_POST['index']) : null; 
     
     if($index <= 1){
      $sql = "SELECT transaction_list,transaction_id,transaction_time FROM coop_account_transaction WHERE user_id = '{$member_no}' AND transaction_list ='XWM' ORDER BY transaction_time DESC LIMIT 0,1";
    $rs = $mysqli->query($sql);
    if ( $rs->num_rows ) {
      $row = $rs->fetch_assoc();
      $isMember = true;
      $transaction_id = $row['transaction_id'];
      $transaction_time = $row['transaction_time'];
    }
     }else{
      $sql = "SELECT share_collect,share_payable,share_date,share_bill FROM coop_mem_share WHERE member_id = '{$member_no}' ORDER BY share_date DESC LIMIT 0,1";
      $rs = $mysqli->query($sql);
      if ( $rs->num_rows ) {
        $row = $rs->fetch_assoc();
        $isMember = true;
        $share_collect = $row['share_collect'];
        $share_payable = $row['share_payable'];
        $transaction_time = $row['share_date'];
        $transaction_id = $row['share_bill'];
      }
     }
    
    if($member_no) {
      require_once("setting_pdf.php");
      // require_once("get_member.php");
      // require_once("get_receipt.php");
      // if ($isMember AND $isReceipt) {
      //   require_once("setting_pdf.php");
      // } else {
      //   header("Location: ".BASE_URL."/bill/pagenotfound");
      //   exit();
      // }
    } else {
      header("Location: ".BASE_URL."/bill/pagenotfound");
      exit();
    }

    $data['url'] = 'https://dev.policehospital-coop.com/APIs/bill/receipt.php?is_base64=true';
    // $data['member'] = '3333333 :'.$accountName;

    echo json_encode($data); 
    exit();

?>