<?php 
    require_once("config.inc.php");
    require_once("token.validate.php");

    $data = [ 'login_status' => 0, 'message_status' => 0, 'responseText' => '' ];

    $sql = "SELECT platform, login_date FROM login_session WHERE member_id = '{$member_id}' AND is_use = 0 ORDER BY login_date DESC LIMIT 1";
    $rs = $mysqli->query($sql);
    if ( $rs->num_rows ) {
        $row = $rs->fetch_assoc();
        $desc = ($row['platform'] == 'website') ? 'เว็บไซต์' : $row['platform'];
        $data['login_status'] = 1;
        $data['lastlogin'] = dateDB2thaidate($row['login_date'], true, false).' ผ่าน '.$desc;
    }

    $sql = "SELECT tb2.msg_id, tb2.msg_title, tb1.user_read, tb2.create_date
    FROM mobile_message_validate AS tb1
    INNER JOIN mobile_message AS tb2 ON tb1.msg_id = tb2.msg_id
    WHERE tb1.member_id = '{$member_id}' AND tb1.user_delete = 0 AND tb2.admin_delete = 0
    ORDER BY tb2.create_date DESC LIMIT 5";
    $rs = $mysqli->query($sql);
    if ( $rs->num_rows ) {

        $data['message_status'] = 1;

        while( ($row = $rs->fetch_assoc()) ){
            $data['data'][] = [
                'msg_id' => $row['msg_id'],
                'msg_title' => $row['msg_title'],
                'is_read' => (int)$row['user_read'],
                'create_date' => dateDB2thaidate($row['create_date'],false,false)
            ];
        }

        $badgeSQL = "SELECT tb1.msg_id AS badge FROM mobile_message_validate AS tb1
        INNER JOIN mobile_message AS tb2 ON tb1.msg_id = tb2.msg_id
        WHERE trim(tb1.member_id) = trim('{$member_id}') AND tb1.user_delete = 0 AND tb2.admin_delete = 0 AND tb1.user_read = 0";
        $badgeRS = $mysqli->query($badgeSQL);
        $data['badge'] = $badgeRS->num_rows;

    }

    echo json_encode($data); 
    exit();
?>
