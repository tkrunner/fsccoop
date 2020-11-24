<?php

    require_once("config.inc.php");
    require_once("token.validate.php");

    $data = [ 'province' => [], 'amphur' => [], 'current_amphur' => [], 'current_district' => [] ];

    $sql = "SELECT province_id, province_name FROM coop_province ORDER BY province_id ASC";
    $rs = $mysqli->query($sql);
    if ( $rs->num_rows ) {
        while( ($row = $rs->fetch_assoc()) ){
            $data['province'][] = [
                'province_id' => $row['province_id'],
                'province_name' => $row['province_name']
            ];
        }
    }

    $sql = "SELECT province_id, amphur_id, amphur_name FROM coop_amphur ORDER BY amphur_id ASC";
    $rs = $mysqli->query($sql);
    if ( $rs->num_rows ) {
        while( ($row = $rs->fetch_assoc()) ){
            $data['amphur'][] = [
                'province_id' => $row['province_id'],
                'amphur_id' => $row['amphur_id'],
                'amphur_name' => $row['amphur_name']
            ];
        }
    }

    $sql = "SELECT province_id, amphur_id, amphur_name FROM coop_amphur ORDER BY amphur_id ASC";
    $rs = $mysqli->query($sql);
    if ( $rs->num_rows ) {
        while( ($row = $rs->fetch_assoc()) ){
            $data['amphur'][] = [
                'province_id' => $row['province_id'],
                'amphur_id' => $row['amphur_id'],
                'amphur_name' => $row['amphur_name']
            ];
        }
    }

    $sql = "SELECT province_id, amphur_id, district_id, district_name FROM coop_district ORDER BY district_id ASC";
    $rs = $mysqli->query($sql);
    if ( $rs->num_rows ) {
        while( ($row = $rs->fetch_assoc()) ){
            $data['district'][] = [
                'province_id' => $row['province_id'],
                'amphur_id' => $row['amphur_id'],
                'district_id' => $row['district_id'],
                'district_name' => $row['district_name']
            ];
        }
    }

    $sql = "SELECT c_amphur_id, c_province_id FROM coop_mem_apply WHERE member_id = '{$member_id}'";
    $rs = $mysqli->query($sql);
    $row = $rs->fetch_assoc();
    $province_id = ($row['c_province_id']) ? $row['c_province_id'] : 0;
    $amphur_id = ($row['c_amphur_id']) ? $row['c_amphur_id'] : 0;
    $data['ssss'] = $province_id.','.$amphur_id;
    $sql = "SELECT province_id, amphur_id, amphur_name FROM coop_amphur WHERE province_id = {$province_id} ORDER BY amphur_id ASC";
    $rs = $mysqli->query($sql);
    if ( $rs->num_rows ) {
        while( ($row = $rs->fetch_assoc()) ){
            $data['current_amphur'][] = [
                'province_id' => $row['province_id'],
                'amphur_id' => $row['amphur_id'],
                'amphur_name' => $row['amphur_name']
            ];
        }
    }

    $sql = "SELECT province_id, amphur_id, district_id, district_name FROM coop_district WHERE province_id = {$province_id} AND amphur_id = {$amphur_id} ORDER BY district_id ASC";
    $rs = $mysqli->query($sql);
    if ( $rs->num_rows ) {
        while( ($row = $rs->fetch_assoc()) ){
            $data['current_district'][] = [
                'province_id' => $row['province_id'],
                'amphur_id' => $row['amphur_id'],
                'district_id' => $row['district_id'],
                'district_name' => $row['district_name']
            ];
        }
    }

    echo json_encode($data); 
    exit();
?>