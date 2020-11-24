<?php

    $token = isset($_POST['token']) ? $mysqli->real_escape_string($_POST['token']) : null;
    $platform = isset($_POST['platform']) ? $mysqli->real_escape_string($_POST['platform']) : null;

    $member_id = '';
    $sql = "SELECT member_id FROM login_session WHERE trim(token) = trim('{$token}') AND trim(platform) = '{$platform}' AND is_use = 1";
    $rs = $mysqli->query($sql);
    if ( $rs->num_rows ) {
        $row = $rs->fetch_assoc();
        $member_id = trim($row['member_id']);
    } else {
        echo json_encode([ 'status' => 0, 'responseText' => 101 ]);
        exit(); 
    }
    
?>