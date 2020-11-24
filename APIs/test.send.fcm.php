<?php
    require_once("config.inc.php");

    $data = [ 'status' => 0, 'responseText' => 'xxxxx' ];

    // $member_id = isset($_POST['member_id']) ? $mysqli->real_escape_string($_POST['member_id']) : null;
    // $msg_title = isset($_POST['msg_title']) ? $mysqli->real_escape_string($_POST['msg_title']) : null;
    // $msg_message = isset($_POST['msg_message']) ? $mysqli->real_escape_string($_POST['msg_message']) : null;

    $member_id = '000049';
    $msg_title = '#ทดสอบการแจ้งเตือน 1';
    $msg_message = 'ทดสอบการแจ้งเตือน';

    $sql = "INSERT INTO mobile_message (msg_title,msg_message,create_date)
            VALUES('{$msg_title}','{$msg_message}',NOW())";
    if ( $mysqli->query($sql) === TRUE ) {
        $msg_id = $mysqli->insert_id;
        $data['$msg_id'] = $msg_id;
        $sql = "INSERT INTO mobile_message_validate (msg_id,member_id)
            VALUES('$msg_id','{$member_id}')";
        if ( $mysqli->query($sql) === TRUE ) {
            $sql = "SELECT mobile_token_message FROM mobile_token WHERE trim(member_id) = trim('{$member_id}') AND is_activate = 1";
            $rs = $mysqli->query($sql);
            if ( $rs->num_rows ) {
                $row = $rs->fetch_assoc();

                $badgeSQL = "SELECT tb1.msg_id AS badge FROM mobile_message_validate AS tb1
                INNER JOIN mobile_message AS tb2 ON tb1.msg_id = tb2.msg_id
                WHERE trim(tb1.member_id) = trim('{$member_id}') AND tb1.user_delete = 0 AND tb2.admin_delete = 0 AND tb1.user_read = 0";
                $badgeRS = $mysqli->query($badgeSQL);

                $response = firebaseCloudMessage($row['mobile_token_message'], $msg_title, $msg_message, $badgeRS->num_rows, $msg_id);
                $data['status'] = 1;
                $data['response'] = $response;
            }
        }
    }

    echo json_encode($data); 
    exit();
?>