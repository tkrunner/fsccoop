<?php

    header('Access-Control-Allow-Headers: X-Requested-With, origin, content-type');

    require_once("config.inc.php");

    $platform = isset($_POST['platform']) ? $mysqli->real_escape_string($_POST['platform']) : null;
    $version = isset($_POST['version']) ? $mysqli->real_escape_string($_POST['version']) : null;

    $info = [ 'android' => '1.12', 'iOS' => '1.4', 'app_status' => 0 ];

    $sql = "SELECT * FROM cmp_application ORDER BY set_id DESC";
    $rs = $mysqli_online->query($sql);
    if ( $rs->num_rows ) {
        $row = $rs->fetch_assoc();
        $data['app_status'] = ($row['set_status'] == 0) ? 0 : 1;
        $data['title'] = $row['set_title'];
        $data['content'] = $row['set_desc'];
        if ( strtolower($platform) == "android" ) {
            $data["is_version"] = ($version == $info['android']) ? true : false;
            $data['store_url'] = 'com.spkt.app';
        } 
        if ( strtolower($platform) == "ios" ) {
            $data["is_version"] = ($version == $info['iOS'] OR $version == '1.4') ? true : false;
            $data['store_url'] = 'https://itunes.apple.com/us/app/spkt/id1435778442?ls=1&mt=8';
        }
    } else {
        if ( strtolower($platform) == "android" ) {
            $data["is_version"] = ($version == $info['android']) ? true : false;
            $data['store_url'] = 'com.spkt.app';
        } 
        if ( strtolower($platform) == "ios" ) {
            $data["is_version"] = ($version == $info['iOS'] OR $version == '1.4') ? true : false;
            $data['store_url'] = 'https://itunes.apple.com/us/app/spkt/id1435778442?ls=1&mt=8';
        }
    }

    echo json_encode($data); 
    exit();
?>