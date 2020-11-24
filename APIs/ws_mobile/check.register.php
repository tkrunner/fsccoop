<?php
    header("Content-type: application/json; charset=UTF-8");
    date_default_timezone_set('Asia/Bangkok');
    require "../config.inc.php";
    // require_once("../parameter.inc.php");
    // require_once("../token.validate.php");
    // require PATH."/class/connect.inc.php";

    $u_id = isset($_POST['uid']) ? $mysqli->real_escape_string($_POST['uid']) : null;
    $platform = isset($_POST['platform']) ? $mysqli->real_escape_string($_POST['platform']) : null;
    $version = isset($_POST['version']) ? $mysqli->real_escape_string($_POST['version']) : null;
    $hour = isset($_POST['hour']) ? $mysqli->real_escape_string($_POST['hour']) : null;
    $minutes = isset($_POST['minutes']) ? $mysqli->real_escape_string($_POST['minutes']) : null;

    // datecheck': datetime,'hour':datetime.getHours(),'minutes':datetime.getMinutes()
     if(strtolower($platform) == 'ios'  AND $version == '1.2'){
        $data['member_id'] = 'demo_device';
        $data['member_id'] = '001316';
      }else{
    $sql = "SELECT member_id FROM mobile_token WHERE mobile_uid = '{$u_id}' AND is_activate = 1 AND is_delete = 0";
    $rs = $mysqli->query($sql);
    $data['row'] = $rs->num_rows;
    if ( $rs->num_rows ) {
        $row = $rs->fetch_assoc();
        $data['member_id'] = $row['member_id'];
    }
     }

    // if ( strtolower($platform) == 'ios' AND $version == $forReview['iOS'] ) {
    //     $data['member_id'] = 'demo_device';
    // }

    $enddate = '';
    $enddate_hour = '';
    $enddate_min = '';

    $sql = "SELECT * FROM cmp_imp_maintenance WHERE is_send = '0'";
    $rs = $mysqli->query($sql);
    $data['row'] = $rs->num_rows;
    if ( $rs->num_rows ) {
        $row = $rs->fetch_assoc();
        $enddate = $row['end_date'];
        $enddate_hour = substr($enddate,10,3);
        $enddate_min = substr($enddate,14,2);
        $data['check'] = $enddate_hour;
        if($hour > $enddate_hour){
            $data['enddatecheck'] = $enddate;
                $sql = "UPDATE cmp_imp_maintenance SET is_send = '1' where is_send = '0' ";     
    }else if($hour == $enddate_hour){
            if($minutes >= $enddate_min){
                $data['enddatecheck'] = $enddate;
                $sql = "UPDATE cmp_imp_maintenance SET is_send = '1' where is_send = '0' ";
                $rs = $mysqli->query($sql);
            }else {
                $data['mainten_id'] = $row['mainten_id'];
                $data['mainten_title'] = $row['mainten_title'];
                $data['mainten_detail'] = $row['mainten_detail'];
                $data['start_date'] = $row['start_date'];
                $data['end_date'] = $row['end_date'];
            }
    }else {
        $data['mainten_id'] = $row['mainten_id'];
        $data['mainten_title'] = $row['mainten_title'];
        $data['mainten_detail'] = $row['mainten_detail'];
        $data['start_date'] = $row['start_date'];
        $data['end_date'] = $row['end_date'];
    }
    }
   

    echo json_encode($data);
    exit();
?>
