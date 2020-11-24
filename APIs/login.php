<?php 
    require_once("config.inc.php");

    $data = [ 'status' => 0, 'responseText' => 'เกิดความผิดพลาดบางประการ กรุณาลองใหม่อีกครั้ง' ];

    $via = isset($_POST['via']) ? $mysqli->real_escape_string($_POST['via']) : null;

    if ( $via == 'website' ) {

        $member_id = isset($_POST['member_id']) ? $mysqli->real_escape_string($_POST['member_id']) : null;
        $password = isset($_POST['password']) ? $mysqli->real_escape_string($_POST['password']) : null;
        $deviceDetail = isset($_POST['deviceDetail']) ? $mysqli->real_escape_string($_POST['deviceDetail']) : null;
        $ip = isset($_POST['ip']) ? $mysqli->real_escape_string($_POST['ip']) : null;

        $sql = "SELECT password, is_active FROM web_online_account WHERE member_id = '{$member_id}' ORDER BY create_date DESC";

        $rs = $mysqli->query($sql);
        if ( $rs->num_rows ) {
            $row = $rs->fetch_assoc();
            if ( !$row['is_active'] ) {
                $data['responseText'] = 'บัญชีนี้ยังไม่ได้รับการยืนยันตน';
                echo json_encode($data); 
                exit();
            }

            if ($password == decrypt_text($row['password'])) {
                $token = get_token($member_id);

                $sql = "UPDATE login_session SET is_use = 0 WHERE member_id = '{$member_id}'";
                $mysqli->query($sql);

                $sql = "INSERT INTO login_session (member_id,token,platform,detail,login_type,login_date,ip_address)
                VALUES('{$member_id}','{$token}','website','{$deviceDetail}','PC',NOW(),'{$ip}')";
                if ( $mysqli->query($sql) === TRUE ) {
                    $data['status'] = 1;
                    $data['responseText'] = '';
                    $data['token'] = $token;
                }
            } else {
                $data['responseText'] = 'รหัสผ่านผิดพลาด กรุณาลองใหม่อีกครั้ง';
            }
        } else {
            $data['responseText'] = 'ไม่พบบัญชี้นี้ในระบบ';
        }

    } else {

    }

    echo json_encode($data); 
    exit();
?>