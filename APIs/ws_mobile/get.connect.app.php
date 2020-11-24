<?php
    header("Content-type: application/json; charset=UTF-8");
    date_default_timezone_set('Asia/Bangkok');
    require_once("../config.inc.php");
    // require PATH."/class/function.inc.php";

    
    $data = array();

            $data['url_connect'] = 'https://system.bbcoop.or.th';
            // $data['mobile_id'] = $mysqli->insert_id;
    echo json_encode($data); 
    exit();
?>
