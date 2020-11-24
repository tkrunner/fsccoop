<?php
    require_once("config.inc.php");

    $data = [ 'status' => 0 ];

    require_once("parameter.inc.php");
    require_once("token.validate.php");

    $password = isset($_POST['password']) ? $mysqli->real_escape_string($_POST['password']) : null;
    $encrypt_password = md5($password);

    $sql = "UPDATE web_online_account SET password = '{$encrypt_password}' WHERE member_id = '{$member_id}' AND is_active = 1 AND is_delete = 0";
    if ( $mysqli->query($sql) === TRUE ) {
        $data['status'] = 1;
    } else {
        $data['responseText'] = 'ไม่สามารถเปลี่ยนรหัสผ่านได้';
    }

    echo json_encode($data); 
    exit();
?>