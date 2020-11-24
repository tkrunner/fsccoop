<?php 
    require_once("config.inc.php");
    require_once("token.validate.php");

    $data_type = isset($_POST['data_type']) ? $mysqli->real_escape_string($_POST['data_type']) : null;
    $data_edit = isset($_POST['data_edit']) ? $mysqli->real_escape_string($_POST['data_edit']) : null;

    $data = [ 'status' => 0, 'responseText' => 'เกิดความผิดพลาดบางประการ กรุณาลองใหม่อีกครั้ง' ];

    $set = "";

    if ( $data_type == "address" ) {
        $data_object = json_decode(str_replace("\\","",$data_edit));
        $set = "
            c_address_no = '{$data_object->c_address_no}',
            c_address_moo = '{$data_object->c_address_moo}',
            c_address_village = '{$data_object->c_address_village}',
            c_address_road = '{$data_object->c_address_road}',
            c_address_soi = '{$data_object->c_address_soi}',
            c_district_id = {$data_object->c_district_id},
            c_amphur_id = {$data_object->c_amphur_id},
            c_province_id = {$data_object->c_province_id},
            c_zipcode = '{$data_object->c_zipcode}'
        ";
    } else if ( $data_type == "tel" ) {
        $set = "tel = '{$data_edit}'";
    } else if ( $data_type == "mobile" ) {
        $set = "mobile = '{$data_edit}'";
    } else if ( $data_type == "email" ) {
        $set = "email = '{$data_edit}'";
    }

    $sql = "UPDATE coop_mem_apply SET ".$set." WHERE member_id = '{$member_id}'";
    if ( $mysqli->query($sql) === TRUE ) {
        $data['status'] = 1;
        $data['responseText'] = '';
    }

    echo json_encode($data); 
    exit();
?>