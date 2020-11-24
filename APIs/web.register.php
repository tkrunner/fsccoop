<?php
    require_once("config.inc.php");

    $data = [ 'status' => 0, 'responseText' => '' ];

    $member_id = isset($_POST['member_id']) ? $mysqli->real_escape_string($_POST['member_id']) : null;
    $idCard = isset($_POST['idCard']) ? $mysqli->real_escape_string($_POST['idCard']) : null;
    $mobile = isset($_POST['mobile']) ? $mysqli->real_escape_string($_POST['mobile']) : null;

    $sql = "SELECT mem_apply_id, member_status FROM coop_mem_apply 
    WHERE member_id = '{$member_id}' AND id_card = '{$idCard}' AND mobile = '{$mobile}'";
    $rs = $mysqli->query($sql);
    if ( $rs->num_rows ) {
        $row = $rs->fetch_assoc();
        
        if ( $row['member_status'] == 2 ) {
            $data['responseText'] = 'ขออภัยท่านไม่สามารถใช้งานระบบได้ เนื่องจากท่านได้ลาออกแล้ว';
            echo json_encode($data); 
            exit();
        } else if ( $row['member_status'] == 3 ) {
            $data['responseText'] = 'ขออภัยท่านไม่สามารถใช้งานระบบได้ เนื่องจากสถานะของท่านรอการอนุมัติ';
            echo json_encode($data); 
            exit();
        }

        $number_otp = rand(100000, 999999);
        $time_start = date("Y-m-d H:i:s");
        $time_end = date("Y-m-d H:i:s", strtotime("+5 minutes"));
        $date_end_msg = date("d-m-Y H:i:s",strtotime($time_end)).' น.';
        $msg = "รหัส OTP สำหรับการดำเนินการคือ {$number_otp} ใช้ได้ถึง {$date_end_msg}";
        $status_sms = send_sms_by_spktcoop($mobile, $msg);
        //$status_sms = send_sms($mobile, $msg);
        $number_otp = (string)$number_otp;
        $data['timeOut'] = $time_end;

        $sql = "SELECT web_id FROM web_online_account WHERE member_id = '{$member_id}' AND is_delete = 0 ORDER BY create_date DESC LIMIT 1";
        $rs = $mysqli->query($sql);
        if ( $rs->num_rows ) {
            $row = $rs->fetch_assoc();
            $web_id = $row['web_id'];
            $sql = "UPDATE web_online_account SET OTP = '{$number_otp}',OTP_start = '{$time_start}',OTP_end = '{$time_end}' WHERE web_id = {$web_id}";
            if ( $mysqli->query($sql) === TRUE ) {
                $data['status'] = 1;
                $data['account_id'] = $web_id;
            }
        } else {
            $sql = "UPDATE web_online_account SET is_delete = 1 WHERE member_id = '{$member_id}'";
            if ( $mysqli->query($sql) === TRUE )

            $sql = "INSERT INTO web_online_account (member_id,OTP,OTP_start,OTP_end,is_active,is_delete,create_date)
            VALUES('{$member_id}','{$number_otp}','{$time_start}','{$time_end}',0,0,NOW())";
            if ( $mysqli->query($sql) === TRUE ) {
                $data['status'] = 1;
                $data['account_id'] = $mysqli->insert_id;
            }
        }
    } else {
        $data['responseText'] = 'ข้อมูลไม่ถูกต้อง กรุณาติดต่อสหกรณ์';
    }

    echo json_encode($data); 
    exit();
?>
