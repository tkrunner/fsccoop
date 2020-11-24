<?php
    require_once("config.inc.php");

    $data = [ 'status' => 0, 'responseText' => '' ];

    $member_id = isset($_POST['member_id']) ? $mysqli->real_escape_string($_POST['member_id']) : null;
    $mobile_id = isset($_POST['mobile_id']) ? $mysqli->real_escape_string($_POST['mobile_id']) : null;
    $via = isset($_POST['via']) ? $mysqli->real_escape_string($_POST['via']) : null;

    $mobile = '';
    $sql = "SELECT mobile FROM coop_mem_apply WHERE member_id = '{$member_id}'";
    $rs = $mysqli->query($sql);
    if ( $rs->num_rows ) {
        $row = $rs->fetch_assoc();
        $mobile = $row['mobile'];
    }

    $number_otp = rand(100000, 999999);
    $time_start = date("Y-m-d H:i:s");
    $time_end = date("Y-m-d H:i:s", strtotime("+5 minutes"));
    $date_end_msg = date("d-m-Y H:i:s",strtotime($time_end)).' น.';
    $msg = "รหัส OTP สำหรับการดำเนินการคือ {$number_otp} ใช้ได้ถึง {$date_end_msg}";
    //$status_sms = send_sms($mobile, $msg);
    $status_sms = send_sms_by_spktcoop($mobile, $msg);
    $number_otp = (string)$number_otp;

    $t = explode('=',explode(',', $status_sms)[0])[1];
    if ( (int)$t == 0 ) {
        $sql = "UPDATE mobile_token SET OTP = '{$number_otp}',OTP_Start = '{$time_start}',OTP_End = '{$time_end}',is_activate = 0 
        WHERE mobile_id = {$mobile_id}";
        if ( $mysqli->query($sql) === TRUE ) {
            $data['status'] = 1;
        } else {
            $data['responseText'] = 'เกิดข้อผิดพลาดบางอย่าง กรุณาลองใหม่อีกครั้ง';
        }
    } else {
        $data['responseText'] = 'เกิดข้อผิดพลาดบางอย่าง กรุณาลองใหม่อีกครั้ง';
    }

    // $data['status_sms'] = $status_sms;
    // $data['t'] = (int)$t;

    echo json_encode($data); 
    exit();
?>