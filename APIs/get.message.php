<?php
    require_once("config.inc.php");
    require_once("token.validate.php");

    $data = [ 'status' => 0, 'responseText' => '' ];

    // $sql = "SELECT tb2.msg_id, tb2.msg_title, tb1.user_read, tb2.create_date
    // FROM mobile_message_validate AS tb1
    // INNER JOIN mobile_message AS tb2 ON tb1.msg_id = tb2.msg_id
    // WHERE trim(tb1.member_id) = trim('{$member_id}') AND tb1.user_delete = 0 
    // ORDER BY tb2.create_date DESC";
    $sql = "SELECT tb1.msg_id, tb1.msg_title, tb1.create_date,tb3.user_read
    FROM mobile_message AS tb1
		INNER JOIN mobile_message AS tb2 ON tb1.msg_id = tb2.msg_id
		INNER JOIN mobile_message_validate AS tb3 ON tb1.msg_id = tb3.msg_id
		 WHERE tb3.member_id = '{$member_id}' AND tb3.user_delete = 0 
        GROUP BY msg_id
		 ORDER BY tb1.create_date DESC";
    $rs = $mysqli->query($sql);
    if ( $rs->num_rows ) {

        $data['status'] = 1;

        while( ($row = $rs->fetch_assoc()) ){
            $data['data'][] = [
                'msg_id' => $row['msg_id'],
                'msg_title' => $row['msg_title'],
                'is_read' => (int)$row['user_read'],
                'create_date' => dateDB2thaidate($row['create_date'],false,false)
            ];
        }

        // $badgeSQL = "SELECT tb1.msg_id AS badge FROM mobile_message_validate AS tb1
        // INNER JOIN mobile_message AS tb2 ON tb1.msg_id = tb2.msg_id
        // WHERE trim(tb1.member_id) = trim('{$member_id}') AND tb1.user_delete = 0 AND tb2.admin_delete = 0 AND tb1.user_read = 0";
        $badgeSQL = "SELECT  tb1.msg_id AS badge ,tb1.msg_id, tb1.msg_title, tb1.create_date,tb3.user_read
        FROM mobile_message AS tb1
            INNER JOIN mobile_message AS tb2 ON tb1.msg_id = tb2.msg_id
            INNER JOIN mobile_message_validate AS tb3 ON tb1.msg_id = tb3.msg_id
             WHERE tb3.member_id = '{$member_id}' AND tb3.user_delete = 0 AND tb3.user_read = 0
            GROUP BY msg_id
             ORDER BY tb1.create_date DESC";
        
        $badgeRS = $mysqli->query($badgeSQL);
        $data['badge'] = $badgeRS->num_rows;

    }

    echo json_encode($data);
    exit();
?>