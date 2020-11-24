<?php
    require_once("config.inc.php");

    $data = [ 'status' => 0, 'responseText' => '' ];
    
    $account_id = isset($_POST['account_id']) ? $mysqli->real_escape_string($_POST['account_id']) : null;
    $password = isset($_POST['password']) ? $mysqli->real_escape_string($_POST['password']) : null;
    $encrypt_password = md5($password);

    $sql = "UPDATE web_online_account SET password = '{$encrypt_password}', is_active = 1 WHERE web_id = {$account_id}";
    if ( $mysqli->query($sql) === TRUE ) {
        $data['status'] = 1;
    } else {
        $data['responseText'] = 'ไม่สามารถเปลี่ยนรหัสผ่านได้ กรุณาติดต่อสหกรณ์';
    }

    echo json_encode($data); 
    exit();
?>