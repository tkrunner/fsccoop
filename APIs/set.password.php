<?php 
    require_once("config.inc.php");

    $data = [ 'status' => 0, 'responseText' => 'เกิดความผิดพลาดบางประการ กรุณาลองใหม่อีกครั้ง' ];

    $account_id = isset($_POST['account_id']) ? $mysqli->real_escape_string($_POST['account_id']) : null;
    $password = isset($_POST['password']) ? $mysqli->real_escape_string($_POST['password']) : null;
    $via = isset($_POST['via']) ? $mysqli->real_escape_string($_POST['via']) : null;

    $encry_password = encrypt_text($password);

    if ( $via == 'website' ) {
        $sql = "UPDATE web_online_account SET password = '{$encry_password}', is_active = 1 WHERE web_id = {$account_id}";
    } else {

    }

    if ( $mysqli->query($sql) === TRUE ) {
        $data['status'] = 1;
        $data['responseText'] = '';
    }

    echo json_encode($data); 
    exit();
?>