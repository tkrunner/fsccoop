<?php
     require_once("../config.inc.php");

     $data = [ 'status' => 0, 'responseText' => '1111' ];
    
    $member = isset($_POST['member_id']) ? $mysqli->real_escape_string($_POST['member_id']) : null;
    $idCard = isset($_POST['idCard']) ? $mysqli->real_escape_string($_POST['idCard']) : null;
    $mobile = isset($_POST['mobile']) ? $mysqli->real_escape_string($_POST['mobile']) : null;
    $mobile_uid = isset($_POST['mobile_uid']) ? $mysqli->real_escape_string($_POST['mobile_uid']) : null;
    $platform = isset($_POST['platform']) ? $mysqli->real_escape_string($_POST['platform']) : null;
    $via = isset($_POST['via']) ? $mysqli->real_escape_string($_POST['via']) : null;
    $pinOld = isset($_POST['pinOld']) ? $mysqli->real_escape_string($_POST['pinOld']) : null;
    $PIN = isset($_POST['pinNew']) ? $mysqli->real_escape_string($_POST['pinNew']) : null;
    if($via == 'mobile_forgot'){
        $PIN = md5($pinOld);
    }else{
        $PIN = md5($PIN);
        
    }
    
    
    // $idCard = isset($_POST['idCard']) ? $mysqli->real_escape_string($_POST['idCard']) : null;
    // $mobile = isset($_POST['mobile']) ? $mysqli->real_escape_string($_POST['mobile']) : null;
    // $mobile_uid = isset($_POST['mobile_uid']) ? $mysqli->real_escape_string($_POST['mobile_uid']) : null;
    // $mobile_token_message = isset($_POST['token_message']) ? $mysqli->real_escape_string($_POST['token_message']) : null;
    //mobile 
    // $dataString = isset($_POST['data']) ? $mysqli->real_escape_string($_POST['data']) : null;
    // $dataObject = json_decode(str_replace("\\", "", $dataString));
    // $member = $dataObject->member;
    // $is_platform = $dataObject->platform;
    // $mobile_uid = $dataObject->mobileUid;
    // $idCard = $dataObject->idCard;
    // $mobile = $dataObject->mobile;
    // $mobile_token_message = $dataObject->mobile_token_message;
    // $PIN = md5($dataObject->PIN);
    // // $PIN = $dataObject->PIN;
    // $FINGER = $dataObject->finger;
    // $FACEID = $dataObject->faceID;

    $member_id = '';
    if ( strlen(trim($member)) == 1 ) {
        $member_id = '00000'.$member;
    } else if ( strlen(trim($member)) == 2 ) {
        $member_id = '0000'.$member;
    } else if ( strlen(trim($member)) == 3 ) {
        $member_id = '000'.$member;
    } else if ( strlen(trim($member)) == 4 ) {
        $member_id = '00'.$member;
    } else if ( strlen(trim($member)) == 5 ) {
        $member_id = '0'.$member;
    } else if ( strlen(trim($member)) == 6 ) {
        $member_id = $member;
    }

    $sql = "SELECT mem_apply_id, member_status FROM coop_mem_apply 
    WHERE member_id = '{$member}' AND id_card = '{$idCard}' AND mobile = '{$mobile}'";
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

    //     // $number_otp = rand(100000, 999999);
        $time_start = date("Y-m-d H:i:s");
        $time_end = date("Y-m-d H:i:s", strtotime("+5 minutes"));
    //     // $date_end_msg = date("d-m-Y H:i:s",strtotime($time_end)).' น.';
    //     // $msg = "รหัส OTP สำหรับการดำเนินการคือ {$number_otp} ใช้ได้ถึง {$date_end_msg}";
    //     // //$status_sms = send_sms($mobile, $msg);
    //     // $status_sms = send_sms_by_spktcoop($mobile, $msg);
    //     // $number_otp = (string)$number_otp;

        $mobile_sql = "SELECT mobile_id,mobile_uid,mobile_PIN FROM mobile_token WHERE member_id = '{$member_id}' AND is_delete = 0 ORDER BY create_date DESC LIMIT 1";
        $mobile_rs = $mysqli->query($mobile_sql);
        if ( $mobile_rs->num_rows ) {
            $mobile_row = $mobile_rs->fetch_assoc();
            $mobile_id = $mobile_row['mobile_id'];
            if($mobile_row['mobile_PIN'] == $PIN ){
                $data['status'] = 1;
                $data['mobile_id'] = $mobile_id;
            }else{
                $data['responseText'] = 'รหัสPinเก่าผิด กรุณาลองใหม่อีกครั้ง';
            }
            if($via != 'mobile_forgot'){
                if ( $mobile_row['mobile_uid'] == $mobile_uid ) {
                    $sql = "UPDATE mobile_token SET mobile_PIN = '{$PIN}',is_activate = 1, platform = '{$is_platform}'
                    WHERE mobile_id = {$mobile_id}";
                    if ( $mysqli->query($sql) === TRUE ) {
                        $data['status'] = 1;
                        $data['mobile_id'] = $mobile_id;
                        // $data['member'] = $dataObject->member;
                        $data['sql'] = $sql;
                    }else{
                        $data['responseText'] = 'เปลี่ยนรหัสไม่สำเร็จ';
                    }
            }
            }
            // if ( trim($mobile_row['mobile_uid']) == trim($mobile_uid) ) {
            //     $sql = "UPDATE mobile_token SET mobile_PIN = '{$PIN}',is_faceid='{$FACEID}',is_finger ='{$FINGER}',is_activate = 1, platform = '{$is_platform}'
            //     WHERE mobile_id = {$mobile_id}";
            //     if ( $mysqli->query($sql) === TRUE ) {
            //         $data['status'] = 1;
            //         $data['mobile_id'] = $mobile_id;
            //         $data['member'] = $dataObject->member;
            //         $data['sql'] = $sql;
            //     }
            } else {
                $data['responseText'] = 'ระบบขัดข้อง';
                // $sql = "UPDATE mobile_token SET is_delete = 1 WHERE mobile_id = {$mobile_id}";
                // if ( $mysqli->query($sql) === TRUE )

                // $sql = "INSERT INTO mobile_token (mobile_uid,mobile_token_message,member_id,OTP,OTP_Start,OTP_End,is_faceid,is_finger,is_activate,create_date,platform)
                // VALUES('{$mobile_uid}','{$mobile_token_message}','{$member_id}','{$number_otp}','{$time_start}','{$time_end}','{$FACEID}','{$FINGER}','1',NOW(),'{$is_platform}')";
                // if ( $mysqli->query($sql) === TRUE ) {
                //     $data['status'] = 1;
                //     $data['mobile_id'] = $mysqli->insert_id;
                //     $data['sql'] = $sql;
                // }
            }
        } else {
            // $sql = "INSERT INTO mobile_token (mobile_uid,mobile_token_message,member_id,OTP,OTP_Start,OTP_End,create_date,platform)
            // VALUES('{$mobile_uid}','{$mobile_token_message}','{$member_id}','{$number_otp}','{$time_start}','{$time_end}',NOW(),'{$is_platform}')";
            // if ( $mysqli->query($sql) === TRUE ) {
            //     $data['status'] = 1;
            //     $data['mobile_id'] = $mysqli->insert_id;
            // }
            $data['responseText'] = 'ลงทะเบียนไม่สำเร็จ';

        }

    //  else {
    //     $text = "ไม่สามารถสมัคร Account ได้ member_id: ".$member_id.", idCard: ".$idCard.", mobile: ".$mobile." / รหัส mobile uid : ".$mobile_uid;
    //     $sql = "INSERT INTO mobile_log (member_id,page,create_date,log_text)
    //     VALUES('{$member_id}','register_mobile',NOW(),'{$text}')";
    //     $mysqli->query($sql);
    //     $sql1 = "SELECT mem_apply_id, member_status FROM coop_mem_apply 
    //     WHERE trim(member_id) = '{$member}' AND trim(id_card) = '{$dataObject->platform}' AND trim(mobile) = '{$dataObject->mobile}'";
    //     $data['responseText'] = 'ข้อมูลของคุณไม่ถูกต้อง<br/>กรุณาตรวจสอบอีกครั้ง<br/>หากไม่สามารถใช้งานได้<br/>กรุณาติดต่อสหกรณ์<br/>0-2384-2493-4 หรือ 0-2756-3995';
    //     $data['text'] = $sql1;
    // }

    echo json_encode($data); 
    exit();
?>

<!-- http://spktcoop.com/uploads/members/ -->