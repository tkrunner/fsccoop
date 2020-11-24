<?php
    require_once("config.inc.php");
    require_once("parameter.inc.php");
    require_once("token.validate.php");

    $data = [ 'status' => 0, 'province' => [], 'amphur' => [] ];

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

    $sql = "SELECT tb1.c_address_no, tb1.c_address_moo, tb1.c_address_village, tb1.c_address_road, tb1.c_address_soi, tb1.c_province_id, tb1.c_amphur_id, tb1.c_district_id, tb1.c_zipcode
    FROM coop_mem_apply AS tb1
    WHERE tb1.member_id = '{$member_id}'";
    $rs = $mysqli->query($sql);
    if ( $rs->num_rows ) {
        $data['status'] = 1;
        $row = $rs->fetch_assoc();
        $data['edit_address'] = [
            'c_address_no'          =>      ($row['c_address_no'] == null) ? '' : $row['c_address_no'],
            'c_address_moo'         =>      ($row['c_address_moo'] == null) ? '' : $row['c_address_moo'],
            'c_address_village'     =>      ($row['c_address_village'] == null) ? '' : $row['c_address_village'],
            'c_address_road'        =>      ($row['c_address_road'] == null) ? '' : $row['c_address_road'],
            'c_address_soi'         =>      ($row['c_address_soi'] == null) ? '' : $row['c_address_soi'],
            'c_province_id'         =>      ($row['c_province_id'] == null) ? 0 : $row['c_province_id'],
            'c_amphur_id'           =>      ($row['c_amphur_id'] == null) ? 0 : $row['c_amphur_id'],
            'c_district_id'         =>      ($row['c_district_id'] == null) ? 0 : $row['c_district_id'],
            'c_zipcode'             =>      ($row['c_zipcode'] == null) ? '' : $row['c_zipcode']
        ];
    }

    echo json_encode($data); 
    exit();
?>