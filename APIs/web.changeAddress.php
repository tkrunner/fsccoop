<?php
    require_once("config.inc.php");

    $data = [ 'status' => 0, 'responseText' => 'ไม่สามารถแก้ไขข้อมูลได้ กรุณาลองใหม่อีกครั้ง' ];

    require_once("parameter.inc.php");
    require_once("token.validate.php");

    $no = isset($_POST['no']) ? $mysqli->real_escape_string($_POST['no']) : null;
    $moo = isset($_POST['moo']) ? $mysqli->real_escape_string($_POST['moo']) : null;
    $village = isset($_POST['village']) ? $mysqli->real_escape_string($_POST['village']) : null;
    $soi = isset($_POST['soi']) ? $mysqli->real_escape_string($_POST['soi']) : null;
    $road = isset($_POST['road']) ? $mysqli->real_escape_string($_POST['road']) : null;
    $zipcode = isset($_POST['zipcode']) ? $mysqli->real_escape_string($_POST['zipcode']) : null;
    $province_id = isset($_POST['province_id']) ? $mysqli->real_escape_string($_POST['province_id']) : null;
    $amphur_id = isset($_POST['amphur_id']) ? $mysqli->real_escape_string($_POST['amphur_id']) : null;
    $district_id = isset($_POST['district_id']) ? $mysqli->real_escape_string($_POST['district_id']) : null;

    $sql = "UPDATE coop_mem_apply SET 
    c_address_no = '{$no}',
    c_address_moo = '{$moo}',
    c_address_village = '{$village}',
    c_address_road = '{$road}',
    c_address_soi = '{$soi}',
    c_district_id = '{$district_id}',
    c_amphur_id = '{$amphur_id}',
    c_province_id = '{$province_id}',
    c_zipcode = '{$zipcode}'
    WHERE member_id = '{$member_id}'";

    if ( $mysqli->query($sql) === TRUE ) {
        $data['status'] = 1;
        $data['responseText'] = '';
    } 

    echo json_encode($data); 
    exit();
?>