<?php
    require_once("config.inc.php");

    $data = [ 'status' => 0, 'responseText' => '' ];

    require_once("parameter.inc.php");
    require_once("token.validate.php");

    $badgeSQL = "SELECT tb1.msg_id AS badge FROM mobile_message_validate AS tb1
    INNER JOIN mobile_message AS tb2 ON tb1.msg_id = tb2.msg_id
    WHERE tb1.member_id = '{$member_id}' AND tb1.user_delete = 0 AND tb2.admin_delete = 0 AND tb1.user_read = 0";
    $badgeRS = $mysqli->query($badgeSQL);
    if ( $badgeRS->num_rows ) {
        $data['status'] = 1;
        $data['badge'] = $badgeRS->num_rows;       
    }

    echo json_encode($data); 
    exit();
?>