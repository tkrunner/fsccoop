<?php
require "../config.inc.php";
// require PATH."/class/connect.inc.php";

header("Content-type: application/json; charset=UTF-8");
date_default_timezone_set('Asia/Bangkok');
    $platform = isset($_POST['platform']) ? $mysqli->real_escape_string($_POST['platform']) : null;
    $version = isset($_POST['version']) ? $mysqli->real_escape_string($_POST['version']) : null;

    $info = [ 'android' => '1.0', 'iOS' => '1.0', 'app_status' => 0 ];

    if ( strtolower($platform) == "android" ) {
        $data["is_version"] = ($version == $info['android'] || $version == '1.0.1' || $version == '1.0.2' || $version == '1.0' ) ? true : false;
        $data['store_url'] = 'https://play.google.com/store/apps/details?id=com.pgh.app';
    } 
    if ( strtolower($platform) == "ios" ) {
       $data["is_version"] = ($version == $info['iOS'] OR $version == '1.1') ? true : false;
       $data['store_url'] = 'https://apps.apple.com/us/app/id1527956097';
    }

    echo json_encode($data); 
    exit();
?>
