<?php
    require_once("config.inc.php");

    $data = [ 'status' => 0, 'responseText' => '' ];

    $device_uid = isset($_POST['device_uid']) ? $mysqli->real_escape_string($_POST['device_uid']) : null;

    $sql = "SELECT is_finger, member_id FROM mobile_token WHERE mobile_uid = '{$device_uid}' AND is_activate = 1";
    $rs = $mysqli->query($sql);

    if ( $rs->num_rows ) {
        $row = $rs->fetch_assoc();
        if ( $row['is_finger'] ) {
            $data['status'] = 1;
        } else {
            $data['responseText'] = 'คุณยังไม่ได้เปิดใช้โหมด Touch ID';
        }
    } else {
        $data['responseText'] = 'คุณยังไม่ได้สมัครใช้งาน Application';
    }

    echo json_encode($data); 
    exit();
?>