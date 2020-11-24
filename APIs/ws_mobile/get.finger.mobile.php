<?php
    require_once("../config.inc.php");

   // $data = [ 'status' => 0, 'responseText' => '' ];

    $device_uid = isset($_POST['device_uid']) ? $mysqli->real_escape_string($_POST['device_uid']) : null;

    $sql = "SELECT is_finger FROM mobile_token WHERE mobile_uid = '{$device_uid}' AND is_finger = 1 AND is_delete = 0 AND is_activate = 1";
    $rs = $mysqli->query($sql);
    if ( $rs->num_rows ) {
        $data['status'] = 1;
    }else{
        $data['status'] = 0;
    }

    echo json_encode($data); 
    exit();
?>