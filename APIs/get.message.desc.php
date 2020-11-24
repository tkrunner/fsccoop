<?php
    require_once("config.inc.php");
    require_once("token.validate.php");

    $data = [ 'status' => 0, 'responseText' => '' ];

    $msg_id = isset($_POST['msg_id']) ? $mysqli->real_escape_string($_POST['msg_id']) : null;
    @$approve = isset($_POST['approve']) ? $mysqli->real_escape_string($_POST['approve']) : null;
    

    $sql = "SELECT msg_id, msg_title, msg_message, create_date,status_text FROM mobile_message WHERE msg_id = {$msg_id}";
    $rs = $mysqli->query($sql);
    if ( $rs->num_rows ) {
        $row = $rs->fetch_assoc();
        $data['status'] = 1;
        $btn1 = '';
        $btn2='';
        $status_open = null;
        if($row['status_text'] == 1){
            $status_open = 1;
            $btn1 = 'ตอบรับ';
            $btn2 = 'ไม่ตอบรับ';
        }else if($row['status_text'] == 2){
            $status_open = 1;
            $btn1 = 'ตกลง';
            $btn2 = 'ไม่ตกลง';
        }else if($row['status_text'] == 3){
            $status_open = 1;
            $btn1 = 'ไลค์';
            $btn2 = 'ไม่ไลค์';
        }else if($row['status_text'] == 0){
            $status_open = 0;
        }
        $data['data'] = [
            'msg_id'    => $row['msg_id'],
            'msg_title' => $row['msg_title'],
            'msg_message' => $row['msg_message'],
            'create_date' => dateDB2thaidate($row['create_date'],false,false),
            'status_open' => $status_open,
            'btn1' => $btn1,
            'btn2' => $btn2,
        ];
        $sql = "SELECT msg_id,status_user_apporve FROM mobile_message_validate  WHERE trim(member_id) = '{$member_id}' AND msg_id = {$msg_id}";
        $rs = $mysqli->query($sql);
        if ( $rs->num_rows ) {
            $row = $rs->fetch_assoc();
            $data['status'] = 1;
            $data['status_approve'] = [
                'status_user_apporve'    => $row['status_user_apporve'],
                // 'msg_title' => $row['msg_title'],
                // 'msg_message' => $row['msg_message'],
                // 'create_date' => dateDB2thaidate($row['create_date'],false,false)
            ];
        }

        $sql = "UPDATE mobile_message_validate SET user_read = 1 WHERE member_id = '{$member_id}' AND msg_id = {$msg_id}";
        if ( $mysqli->query($sql) === TRUE );
    }

    if($approve == 'true'){
        $sql = "UPDATE mobile_message_validate SET status_user_apporve = 1 WHERE member_id = '{$member_id}' AND msg_id = {$msg_id}";
        if ( $mysqli->query($sql) === TRUE ){
            $data['status_user_apporve'] = 1;
        };
        
        
    }else if($approve == 'false'){
        $sql = "UPDATE mobile_message_validate SET status_user_apporve = 2 WHERE member_id = '{$member_id}' AND msg_id = {$msg_id}";
        if ( $mysqli->query($sql) === TRUE ){
            $data['status_user_apporve'] = 1;
        };
    }

    echo json_encode($data);
    exit();
?>