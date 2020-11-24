<?php
    require_once("config.inc.php");
    require_once("token.validate.php");

    $data = [ 'status' => 0, 'responseText' => '' ];

    $msg_id = isset($_POST['msg_id']) ? $mysqli->real_escape_string($_POST['msg_id']) : null;
    
    $sql = "UPDATE mobile_message_validate SET user_delete = 1, user_delete_date = NOW() WHERE msg_id = {$msg_id} AND member_id = '{$member_id}'";
    if ( $mysqli->query($sql) === TRUE ) {
        $data['status'] = 1;
        $badgeSQL = "SELECT tb1.msg_id AS badge FROM mobile_message_validate AS tb1
        INNER JOIN mobile_message AS tb2 ON tb1.msg_id = tb2.msg_id
        WHERE tb1.member_id = '{$member_id}' AND tb1.user_delete = 0 AND tb2.admin_delete = 0 AND tb1.user_read = 0";
        $badgeRS = $mysqli->query($badgeSQL);
        $data['badge'] = $badgeRS->num_rows;
    }

    echo json_encode($data); 
    exit();
?>