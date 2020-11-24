<?php

    require_once("config.inc.php");

    $data = [ 'status' => 0, 'responseText' => '' ];

    require_once("parameter.inc.php");
    require_once("token.validate.php");

    $sql = "SELECT 
    CONCAT( tb3.prename_short,' ',tb1.g_firstname,' ',tb1.g_lastname ) AS gain_name,
    tb2.relation_name,
    CASE 
        WHEN tb1.g_mobile = '' THEN IF(tb1.g_tel = '',tb1.g_office_tel,tb1.g_tel)
        ELSE tb1.g_mobile
    END AS gain_mobile,
    CONCAT( tb1.g_share_rate, '%' ) AS gain_percent
    FROM coop_mem_gain_detail AS tb1
    LEFT JOIN coop_mem_relation AS tb2 ON tb1.g_relation_id = tb2.relation_id
    LEFT JOIN coop_prename AS tb3 ON tb1.g_prename_id = tb3.prename_id
    WHERE tb1.member_id = '{$member_id}' ORDER BY g_create ASC";
    $rs = $mysqli->query($sql);
    if ( $rs->num_rows ) {
        $data['status'] = 1;
        while( ($row = $rs->fetch_assoc()) ){
            $data['data'][] = $row;
        }
    }

    echo json_encode($data); 
    exit();

?>