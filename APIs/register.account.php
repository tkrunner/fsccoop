<?php 
    require_once("config.inc.php");

   // $data = [ 'status' => 0, 'responseText' => 'เกิดความผิดพลาดบางประการ', 'account_id' => 0 ];

    $member_id = isset($_POST['member_id']) ? $mysqli->real_escape_string($_POST['member_id']) : null;
    $idCard = isset($_POST['idCard']) ? $mysqli->real_escape_string($_POST['idCard']) : null;
    $mobile = isset($_POST['mobile']) ? $mysqli->real_escape_string($_POST['mobile']) : null;
    $via = isset($_POST['via']) ? $mysqli->real_escape_string($_POST['via']) : null;
    $mobile_uid = isset($_POST['mobile_uid']) ? $mysqli->real_escape_string($_POST['mobile_uid']) : null;
    $platform = isset($_POST['platform']) ? $mysqli->real_escape_string($_POST['platform']) : null;
    $hash = isset($_POST['Hash']) ? $mysqli->real_escape_string($_POST['Hash']) : null;
    
    
    $sql = "SELECT id, member_id, id_card, mobile FROM coop_mem_apply WHERE member_id = '{$member_id}'";
    $token_msg = get_token($member_id);
    //echo $sql;
    $rs = $mysqli->query($sql);
    if ( $rs->num_rows ) {

        $row = $rs->fetch_assoc();

        if ( $row['id_card'] != $idCard)  {
            $data['responseText'] = 'รหัสบัตรประชาชนผิดพลาด';
            echo json_encode($data); 
            exit();
        } 
        if ( $row['mobile'] != $mobile ) {
            $data['responseText'] = 'เบอร์โทรศัพท์ผิดพลาด';
            echo json_encode($data); 
            exit();
        }
        if($via == 'mobile'){
        $number_otp = rand(100000, 999999);
        $time_start = date("Y-m-d H:i:s");
        $time_end = date("Y-m-d H:i:s", strtotime("+60 minutes"));
        $date_end_msg = date("d-m-Y H:i:s",strtotime($time_end)).' น.';
        $msg = "รหัส OTP สำหรับการดำเนินการคือ {$number_otp} ใช้ได้ถึง {$date_end_msg} {$hash}";
        $mobile = str_replace("-", "", $mobile);
      
        $number_otp = (string)$number_otp;
        $token_msg = get_token($member);

        $data['timeOut'] = $time_end;
        }else{
        $number_otp = rand(100000, 999999);
        $time_start = date("Y-m-d H:i:s");
        $time_end = date("Y-m-d H:i:s", strtotime("+60 minutes"));
        $date_end_msg = date("d-m-Y H:i:s",strtotime($time_end)).' น.';
        $msg = "รหัส OTP สำหรับการดำเนินการคือ {$number_otp} ใช้ได้ถึง {$date_end_msg}";
        $mobile = str_replace("-", "", $mobile);
      
        $number_otp = (string)$number_otp;
        $token_msg = get_token($member);

        $data['timeOut'] = $time_end;
        }
        

        if ( $via == 'mobile' ) {
            $status_sms = send_sms($mobile, $msg);

            $updateToken = "UPDATE mobile_token SET is_delete = 1 WHERE  mobile_uid = '{$mobile_uid}' AND  is_delete = '0'";
        $mysqli->query($updateToken);
        $insertToken = "INSERT INTO mobile_token (mobile_uid,mobile_PIN, member_id, OTP, OTP_Start, OTP_End, platform, mobile_token_message,is_activate, create_date) VALUES ('{$mobile_uid}','','{$member_id}', '{$number_otp}', '{$time_start}', '{$time_end}','{$platform}', '{$token_msg}','1',NOW())";

        if($mysqli->query($insertToken) === TRUE){
            $data['member_no'] = $member_id;
            $data['token'] = $token_msg;
            $data['mobile_id'] = $mysqli->insert_id;
        }else{
            $data['responseText'] = 'เกิดข้อผิดพลาดบางอย่าง  กรุณาลองอีกครั้ง หรือสอบถามเจ้าหน้าที่สหกรณ์  ที่เบอร์'."<br>".' 053-851888';
            echo json_encode($data);
            exit();
        }

        } else if ( $via == 'website' ) {
            $sql = "SELECT web_id FROM web_online_account WHERE member_id = '{$member_id}' ORDER BY create_date DESC";
            $rs = $mysqli->query($sql);
            if ( $rs->num_rows ) {
                $row = $rs->fetch_assoc();
                $web_id = $row['web_id'];
                $sql = "UPDATE web_online_account SET OTP = '{$number_otp}', OTP_start = '{$time_start}', OTP_end = '{$time_end}', is_active = 0, is_delete = 0, create_date = NOW() WHERE web_id = {$web_id}";
                if ( $mysqli->query($sql) === TRUE ) {
                    $status_sms = send_sms($mobile, $msg);
                    $data['status'] = 1;
                    $data['responseText'] = '';
                    $data['account_id'] = $web_id;
                }
            } else {
                $sql = "INSERT INTO web_online_account (member_id, OTP, OTP_start, OTP_end, is_active, is_delete, create_date) VALUES('{$member_id}', '{$number_otp}', '{$time_start}', '{$time_end}', 0, 0, NOW())";
                if ( $mysqli->query($sql) === TRUE ) {
                    $last_id = $mysqli->insert_id;
                    $status_sms = send_sms($mobile, $msg);
                    $data['status'] = 1;
                    $data['responseText'] = '';
                    $data['account_id'] = $last_id;
                }
            }
        }

    } else {
        $data['responseText'] = 'ไม่พบบัญชีนี้ในระบบ';
    }

    echo json_encode($data); 
    exit();
?>
