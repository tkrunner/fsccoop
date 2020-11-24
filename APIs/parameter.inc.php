<?php
    //$member_id = isset($_POST['member_id']) ? $mysqli->real_escape_string($_POST['member_id']) : null;
    $token = isset($_POST['token']) ? $mysqli->real_escape_string($_POST['token']) : null;
    $platform = isset($_POST['platform']) ? $mysqli->real_escape_string($_POST['platform']) : null;

    $member_id = '';
    $sql = "SELECT member_id FROM login_session WHERE token = '{$token}' AND platform = '{$platform}' AND is_use = 1";
    $rs = $mysqli->query($sql);
    if ( $rs->num_rows ) {
        $row = $rs->fetch_assoc();
        $member_id = $row['member_id'];
    }     
?>
