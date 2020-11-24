<?php 
    require_once("config.inc.php");

    $data = [ 'status' => 0, 'responseText' => '' ];

    $member_id = isset($_POST['member_id']) ? $mysqli->real_escape_string($_POST['member_id']) : null;
    $mobile_uid = isset($_POST['mobile_uid']) ? $mysqli->real_escape_string($_POST['mobile_uid']) : null;
    $is_event = isset($_POST['is_event']) ? $mysqli->real_escape_string($_POST['is_event']) : null;

    if ( $is_event == "get" ) {
        $sql = "SELECT is_finger FROM mobile_token 
        WHERE mobile_uid = '{$mobile_uid}' AND member_id = '$member_id' LIMIT 1";
        $rs = $mysqli->query($sql);
        if ( $rs->num_rows ) {
            $row = $rs->fetch_assoc();
            $data['status'] = 1;
            $data['is_finger'] = $row['is_finger'];
        }
    } else if ( $is_event == "change" ) {
        $is_finger = isset($_POST['is_finger']) ? $mysqli->real_escape_string($_POST['is_finger']) : null;
        $fingerPrint = (int)$is_finger;
        $sql = "UPDATE mobile_token SET is_finger = {$fingerPrint} 
        WHERE mobile_uid = '{$mobile_uid}' AND member_id = '$member_id'";
        if ( $mysqli->query($sql) === TRUE ) $data['status'] = 1;
    }

    echo json_encode($data); 
    exit();
    
?>