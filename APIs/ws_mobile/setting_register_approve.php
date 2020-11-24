<?php
require "../config.inc.php";
// require PATH."/class/connect.inc.php";

header("Content-type: application/json; charset=UTF-8");
date_default_timezone_set('Asia/Bangkok');
    @$platform = isset($_POST['platform']) ? $mysqli->real_escape_string($_POST['platform']) : null;
    @$version = isset($_POST['version']) ? $mysqli->real_escape_string($_POST['version']) : null;
    $member_no = isset($_POST["member_no"]) ? $_POST["member_no"] : 0 ;
    // $member_no = '009999';
    $statusOpen = 1;
    $array[] = [];
    $member = [ '009999','999999','000146','003466','001346','001979','006346','003300','001316','000995','001462' ];

    foreach ($member as $key => $value) {
        array_push($array,$value);
    }
    // print_r($array);

        $result_array   = array_search($member_no, $array); 

        if($result_array!=FALSE){
            $data['status'] = 'apporve';
           }
           else
           {
            $data['status'] = 'apporve';
            // $data['status'] = 'no apporve';
            // $data['responseText'] = 'เตรียมเปิดให้บริการเร็วๆนี้';
           }
        
           $data['open_check'] = 'true';

    echo json_encode($data); 
    exit();
?>
