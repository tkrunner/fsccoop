<?php
    require_once("config.inc.php");

    $data = [ 'status' => 0, 'responseText' => '', 'app_status' => 0 ];

    $platform = isset($_POST['platform']) ? $mysqli->real_escape_string($_POST['platform']) : null;
    $PIN = isset($_POST['PIN']) ? $mysqli->real_escape_string($_POST['PIN']) : null;
    $encrypt_PIN = md5($PIN);
    $device_uid = isset($_POST['device_uid']) ? $mysqli->real_escape_string($_POST['device_uid']) : null;
    $loginVia = isset($_POST['loginVia']) ? $mysqli->real_escape_string($_POST['loginVia']) : null;
    $token_message = isset($_POST['token_message']) ? $mysqli->real_escape_string($_POST['token_message']) : null;
    
    $review_appstore = true;

    if ( strtolower($platform) == 'ios' OR strtolower($platform) == 'android' ) {
        $version = isset($_POST['version']) ? $mysqli->real_escape_string($_POST['version']) : null;
        //if ( $review_appstore AND $version == '1.1' AND $PIN == '998877' ) {
        if ( $review_appstore AND $PIN == '998877' ) {
            $device_uid = 'demo_device';
        }
    }

    $sql = "SELECT * FROM cmp_application ORDER BY set_id DESC";
    $rs = $mysqli_online->query($sql);
    if ( $rs->num_rows ) {
        $row = $rs->fetch_assoc();

        $data['title'] = $row['set_title'];
        $data['content'] = $row['set_desc'];

        if ( $row['set_status'] == 1 ) {
            $data['app_status'] = 1;
        } else {
            $arr_member = explode(',', $row['set_member']);
            for( $i = 0; $i < COUNT($arr_member); $i++ ) {
                $member_arr = '';
                if ( strlen($arr_member[$i]) == 1 ) {
                    $member_arr = '00000'.$arr_member[$i];
                } else if ( strlen($arr_member[$i]) == 2 ) {
                    $member_arr = '0000'.$arr_member[$i];
                } else if ( strlen($arr_member[$i]) == 3 ) {
                    $member_arr = '000'.$arr_member[$i];
                } else if ( strlen($arr_member[$i]) == 4 ) {
                    $member_arr = '00'.$arr_member[$i];
                } else if ( strlen($arr_member[$i]) == 5 ) {
                    $member_arr = '0'.$arr_member[$i];
                } else if ( strlen($arr_member[$i]) == 6 ) {
                    $member_arr = $arr_member[$i];
                }
    
                // $data['member_list'][] = [  
                //     'member' => $member_arr,
                //     'member_length' => strlen(trim($arr_member[$i])),
                //     '$member_id' => $member_id
                // ];
    
                if ( $member_arr == $member_id ) {
                    $data['app_status'] = 1;
                }
    
            }
        }
    } else {
        $data['app_status'] = 1;
    }

    //For Apple Review
    // if ( strtolower($platform) == 'ios' ) {
    //     $data['app_status'] = 1;
    // }
    
    if ( strtolower($loginVia) == "pin" ) {
        $sql = "SELECT tb1.member_id, tb1.mobile_PIN, tb2.member_status
        FROM mobile_token AS tb1
        INNER JOIN coop_mem_apply AS tb2 ON tb2.member_id = tb1.member_id
        WHERE tb1.mobile_uid = '{$device_uid}' AND tb1.is_delete = 0 AND tb1.is_activate = 1 
        ORDER BY tb1.create_date DESC LIMIT 1";
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

            if ( $row['mobile_PIN'] == $encrypt_PIN ) {
                $token = get_token($row['member_id']);
                $member_id = $row['member_id'];
                
                if ( $token_message ) {
                    $sql = "UPDATE mobile_token SET mobile_token_message = '{$token_message}', platform = '{$platform}' WHERE member_id = '{$member_id}'";
                    $mysqli->query($sql);
                }

                $sql = "UPDATE login_session SET is_use = 0 WHERE member_id = t'{$member_id}'";
                $mysqli->query($sql);
    
                $sql = "INSERT INTO login_session (member_id,token,platform,login_type,login_date)
                VALUES('{$member_id}','{$token}','{$platform}','mobile',NOW())";
                $mysqli->query($sql);
    
                $data['status'] = 1;
                $data['member_id'] = $row['member_id'];
                $data['token'] = $token;
            } else {
                $data['responseText'] = 'รหัสผ่านไม่ถูกต้อง<br/>กรุณาลองใหม่อีกครั้ง';
            }
        } else {
            $data['responseText'] = 'คุณยังไม่ได้สมัครใช้งาน Application';
        }
    } else if ( strtolower($loginVia) == "finger" ) {
        $sql = "SELECT tb1.member_id, tb1.mobile_PIN, tb2.member_status
        FROM mobile_token AS tb1
        INNER JOIN coop_mem_apply AS tb2 ON tb2.member_id = tb1.member_id
        WHERE tb1.mobile_uid = '{$device_uid}' AND tb1.is_delete = 0 AND tb1.is_activate = 1 
        ORDER BY tb1.create_date DESC LIMIT 1";
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

            $token = get_token($row['member_id']);
            $member_id = $row['member_id'];

            if ( $token_message ) {
                $sql = "UPDATE mobile_token SET mobile_token_message = '{$token_message}', platform = '{$platform}' WHERE member_id = '{$member_id}'";
                $mysqli->query($sql);
            }
            
            $sql = "UPDATE login_session SET is_use = 0 WHERE member_id = '{$member_id}'";
            $mysqli->query($sql);

            $sql = "INSERT INTO login_session (member_id,token,platform,login_type,login_date)
            VALUES('{$member_id}','{$token}','{$platform}','mobile',NOW())";
            $mysqli->query($sql);

            $data['status'] = 1;
            $data['member_id'] = $row['member_id'];
            $data['token'] = $token;
        } else {
            $data['responseText'] = 'คุณยังไม่ได้สมัครใช้งาน Application';
        }
    }

    echo json_encode($data); 
    exit();
?>
