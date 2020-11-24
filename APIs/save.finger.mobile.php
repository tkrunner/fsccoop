<?php 
    require_once("config.inc.php");

    $data = [ 'status' => 0, 'responseText' => '' ];

    $member_id = isset($_POST['member_id']) ? $mysqli->real_escape_string($_POST['member_id']) : null;
    $mobile_tokenId = isset($_POST['mobile_tokenId']) ? $mysqli->real_escape_string($_POST['mobile_tokenId']) : null;
    $is_finger = isset($_POST['is_finger']) ? $mysqli->real_escape_string($_POST['is_finger']) : null;

    $fingerPrint = (int)$is_finger;

    // $sql = "UPDATE mobile_token SET is_finger = 1 WHERE trim(mobile_id) = trim('{$mobile_id}') AND trim(mobile_uid) = trim('{$mobile_uid}')";
    $sql = "UPDATE mobile_token SET is_finger = {$fingerPrint} WHERE mobile_id = {$mobile_tokenId}";
    if ( $mysqli->query($sql) === TRUE ) $data['status'] = 1;

    echo json_encode($data); 
    exit();
    
?>