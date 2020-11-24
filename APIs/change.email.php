<?php 
    require_once("config.inc.php");

    $data = [ 'status' => 0, 'responseText' => 'ไม่สามารถแก้ไขข้อมูลได้ กรุณาลองใหม่อีกครั้ง' ];

    require_once("parameter.inc.php");
    require_once("token.validate.php");

    $value = isset($_POST['value']) ? $mysqli->real_escape_string($_POST['value']) : null;

    $sql = "UPDATE coop_mem_apply SET email = '{$value}' WHERE member_id = '{$member_id}'";
    
    if ( $mysqli->query($sql) === TRUE ) {
        $data['status'] = 1;
        $data['responseText'] = '';
    } 

    echo json_encode($data); 
    exit();
    
?>