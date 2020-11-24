<?php
    require_once("config.inc.php");

    $data = [ 'is_register' => 0 ];

    $u_id = isset($_POST['uid']) ? $mysqli->real_escape_string($_POST['uid']) : null;
    $platform = isset($_POST['platform']) ? $mysqli->real_escape_string($_POST['platform']) : null;

    $version = isset($_POST['version']) ? $mysqli->real_escape_string($_POST['version']) : null;

    $sql = "SELECT mobile_uid FROM mobile_token WHERE mobile_uid = '{$u_id}'";
    $rs = $mysqli->query($sql);
    if ( $rs->num_rows ) {
        $row = $rs->fetch_assoc();
        $data['is_register'] = 1;
    }

    if ( strtolower($platform) == 'ios' AND $version == '1.4' ) {
        $data['is_register'] = 1;
    }

    echo json_encode($data);
    exit();
?>