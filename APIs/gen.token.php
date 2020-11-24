<?php

    $token = get_token($member_id);
    $ipaddress = $_SERVER['REMOTE_ADDR'];

    $sql = "UPDATE login_session SET is_use = 0 WHERE member_id = '{$member_id}'";
    $mysqli->query($sql);

    
        $sql = "INSERT INTO login_session (member_id,token,platform,login_type,login_date, ip_address, detail)
            VALUES('{$member_id}','{$token}','{$platform}','website',NOW(), '{$IP}', '{$browser}')";
                 
    $mysqli->query($sql);

    $data['token'] = $token;

?>