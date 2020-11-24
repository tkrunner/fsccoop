<?php
    require_once("config.inc.php");

    $data = [ 'status' => 0, 'responseText' => 'ไม่สามารถดำเนินการได้' ];

    $member_id = isset($_POST['member_id']) ? $mysqli->real_escape_string($_POST['member_id']) : null;
    $ip = isset($_POST['ip']) ? $mysqli->real_escape_string($_POST['ip']) : null;

    $token = get_token($member_id);

    $sql = "INSERT INTO login_session (member_id,token,platform,detail,login_type,login_date,ip_address)
    VALUES('{$member_id}','{$token}','website','web_admin','PC',NOW(),'{$ip}')";
    if ( $mysqli->query($sql) === TRUE ) {
        $data['status'] = 1;
        $data['token'] = $token;
    }

    echo json_encode($data); 
    exit();

?>