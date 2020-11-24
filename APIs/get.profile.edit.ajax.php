<?php

    require_once("config.inc.php");
    require_once("token.validate.php");

    $data = [ 'province' => [], 'amphur' => [], 'current_amphur' => [], 'current_district' => [],'zipcode' => [] ];
	
	$type = isset($_POST['type']) ? $mysqli->real_escape_string($_POST['type']) : null;
	$province_id = isset($_POST['province_id']) ? $mysqli->real_escape_string($_POST['province_id']) : null;
	$amphur_id = isset($_POST['amphur_id']) ? $mysqli->real_escape_string($_POST['amphur_id']) : null;
	$tumbol_id = isset($_POST['tumbol_id']) ? $mysqli->real_escape_string($_POST['tumbol_id']) : null;
 	

	
	if($type == "province" ){
		$sql = "SELECT province_id, province_name FROM coop_province ORDER BY province_name ASC";
		$rs = $mysqli->query($sql);
		if ( $rs->num_rows ) {
			while( ($row = $rs->fetch_assoc()) ){
				$data['province'][] = [
					'province_id' => $row['province_id'],
					'province_name' => trim($row['province_name'])
				];
			}
		}
	}
	
	if($type == "amphur"){
		$sql = "SELECT province_id, amphur_id, amphur_name FROM coop_amphur   WHERE province_id = '{$province_id}' ORDER BY amphur_name ASC ";
		$rs = $mysqli->query($sql);
		if ( $rs->num_rows ) {
			while( ($row = $rs->fetch_assoc()) ){
				$data['amphur'][] = [
					'province_id' => $row['province_id'],
					'amphur_id' => $row['amphur_id'],
					'amphur_name' => trim($row['amphur_name'])
				];
			}
		}
	}
	
	if($type == "district"){
		$sql = "SELECT province_id, amphur_id, district_id, district_name,district_code FROM coop_district WHERE amphur_id = '{$amphur_id}' ORDER BY district_name ASC   ";
		$rs = $mysqli->query($sql);
		if ( $rs->num_rows ) {
			while( ($row = $rs->fetch_assoc()) ){
				$data['district'][] = [
					'province_id' => $row['province_id'],
					'amphur_id' => $row['amphur_id'],
					'district_id' => $row['district_id'],
					'district_name' => trim($row['district_name']),
					'district_code' => $row['district_code'],
				];
			}
		}
	}

	if($type == "zipcode"){
		$sql = "SELECT zipcode, district_code FROM coop_zipcode WHERE district_code = '{$tumbol_id}'";
		$rs = $mysqli->query($sql);
		if ( $rs->num_rows ) {
			while( ($row = $rs->fetch_assoc()) ){
				$data['zipcode'][] = [
					'zipcode' => $row['zipcode'],
					'district_code' => $row['district_code']
				];
			}
		}
	}
     
    echo json_encode($data); 
    exit();
 