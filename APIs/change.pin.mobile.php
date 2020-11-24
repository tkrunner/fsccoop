<?php 
    require_once("config.inc.php");

    $data = [ 'status' => 0, 'responseText' => '' ];

    $member_id = isset($_POST['member_id']) ? $mysqli->real_escape_string($_POST['member_id']) : null;
    $mobile_uid = isset($_POST['mobile_uid']) ? $mysqli->real_escape_string($_POST['mobile_uid']) : null;
    $PIN = isset($_POST['PIN']) ? $mysqli->real_escape_string($_POST['PIN']) : null;

    $encrypt_PIN = md5($PIN);

    $sql = "SELECT mobile_id FROM mobile_token WHERE mobile_uid = '{$mobile_uid}' AND is_delete = 0";
    $rs = $mysqli->query($sql);
    if ( $rs->num_rows ) {
        $row = $rs->fetch_assoc();
        $mobile_id = $row['mobile_id'];
        $sql = "UPDATE mobile_token SET mobile_PIN = '{$encrypt_PIN}', is_activate = 1 WHERE mobile_id = {$mobile_id}";
        if ( $mysqli->query($sql) === TRUE ) $data['status'] = 1;
    } else {
        $data['responseText'] = 'กรุณาตรวจสอบอีกครั้ง หากไม่สามารถใช้งานได้<br/>กรุณาติดต่อสหกรณ์<br/>0-2384-2493-4 หรือ 0-2756-3995';
    }

    echo json_encode($data); 
    exit();
    
?>