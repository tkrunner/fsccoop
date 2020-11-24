<?php
    require_once("config.inc.php");

    $account_id = isset($_POST['account_id']) ? $mysqli->real_escape_string($_POST['account_id']) : null;
    $OTP = isset($_POST['OTP']) ? $mysqli->real_escape_string($_POST['OTP']) : null;

    $data = [ 'status' => 0, 'responseText' => '' ];

    $sql = "SELECT OTP,OTP_End FROM web_online_account WHERE web_id = {$account_id}";
    $rs = $mysqli->query($sql);
    if ( $rs->num_rows ) {
        $row = $rs->fetch_assoc();
        if ( $row['OTP'] == $OTP ) {
            $end_date = date("Y-m-d H:i:s", strtotime($row['OTP_End']));
            if ( $end_date >= date("Y-m-d H:i:s") ) {
                $data['status'] = 1;
            } else {              
                $data['responseText'] =  'OTP หมดอายุตั้งแต่ '.date("d-m-Y H:i:s",strtotime($end_date)).' น.';
            }
        } else {
            $data['responseText'] =  'OTP ไม่ตรงกัน';
        }
    } else {
        $data['responseText'] =  'ไม่พบข้อมูลรหัส OTP นี้ในระบบ';
    }

    echo json_encode($data); 
    exit();

?>