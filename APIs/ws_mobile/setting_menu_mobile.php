<?php
require "../config.inc.php";
// require PATH."/class/connect.inc.php";

header("Content-type: application/json; charset=UTF-8");
date_default_timezone_set('Asia/Bangkok');
    $platform = isset($_POST['platform']) ? $mysqli->real_escape_string($_POST['platform']) : null;
    $version = isset($_POST['version']) ? $mysqli->real_escape_string($_POST['version']) : null;
    $index = isset($_POST["index"]) ? $_POST["index"] : 0 ;
    $statusOpen = 1;
    //$member = [ '009999','999999' ];
    if($statusOpen == 1){
        if($index == 1){
            $data["statusOpen"] = 1;
        }else if ($index == 2){
            $data["statusOpen"] = 1;
        }else if ($index == 3){
            $data["statusOpen"] = 1;
        }else if ($index == 4){
            $data["statusOpen"] = 1;
            $data['msg'] = 'เตรียมเปิดให้บริการเร็วๆนี้';
        }else if ($index == 5){
            $data["statusOpen"] = 1;
            $data['msg'] = 'เตรียมเปิดให้บริการเร็วๆนี้';
        }else if ($index == 0){
            $data["statusOpen"] = 1;
        }
    }else{
        $data["statusOpen"] = 2; 
    }

    // $info = [ 'android' => '1.0', 'iOS' => '1.1', 'app_status' => 0 ];

    // if ( strtolower($platform) == "android" ) {
    //     $data["is_version"] = ($version == $info['android'] || $version == '1.0.1' || $version == '1.0' ) ? true : false;
    //     $data['store_url'] = 'https://play.google.com/store/apps/details?id=com.kusccapp.app';
    // } 
    // if ( strtolower($platform) == "ios" ) {
    //    $data["is_version"] = ($version == $info['iOS'] OR $version == '1.1') ? true : false;
    //    $data['store_url'] = 'https://apps.apple.com/us/app/id1031800477';
    // }

    echo json_encode($data); 
    exit();
?>
