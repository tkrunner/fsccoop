<?php

    require_once("config.inc.php");

    $member_id = isset($_POST['member_no']) ? $mysqli->real_escape_string($_POST['member_no']) : null;
    $password = isset($_POST['password']) ? $mysqli->real_escape_string($_POST['password']) : null;
    $IP = isset($_POST['IP']) ? $mysqli->real_escape_string($_POST['IP']) : null;
    $browser = isset($_POST['browser']) ? $mysqli->real_escape_string($_POST['browser']) : null;
    $platform = isset($_POST['platform']) ? $mysqli->real_escape_string($_POST['platform']) : null;
    // $member_id = convertMemberId($member);

    $sql = "SELECT password FROM web_online_account WHERE member_id = '{$member_id}' AND is_delete = 0 ORDER BY create_date DESC LIMIT 1";

    $rs = $mysqli->query($sql);
    if ( $rs->num_rows ) {
        $row = $rs->fetch_assoc();
        $password_decrypt = decrypt_text($row['password']);
        if ($password_decrypt !== $password) {
            $data['responseError'] = 'รหัสผ่านไม่ถูกต้อง<br/>' ;
        } else {
            require_once("gen.token.php");
        }
    } else {
        $data['responseError'] = 'ไม่พบบัญชีนี้ในระบบ<br/>';
    }

    echo json_encode($data);
    exit();
?>