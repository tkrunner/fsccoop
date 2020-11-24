<?php

    require_once("config.inc.php");
    require_once("token.validate.php");

    $data = [ 'status' => 0, 'responseText' => '', 'is_response_gain' => '', 'is_response_diviend' => '' ];
    $ip = '';
    $token = isset($_POST['token']) ? $mysqli->real_escape_string($_POST['token']) : null;
    $platform = isset($_POST['platform']) ? $mysqli->real_escape_string($_POST['platform']) : null;
    $address_no = isset($_POST['address_no']) ? $mysqli->real_escape_string(trim($_POST['address_no'])) : null;
    $address_moo = isset($_POST['address_moo']) ? $mysqli->real_escape_string(trim($_POST['address_moo'])) : null;
    $address_village = isset($_POST['address_village']) ? $mysqli->real_escape_string(trim($_POST['address_village'])) : null;
    $address_road = isset($_POST['address_road']) ? $mysqli->real_escape_string(trim($_POST['address_road'])) : null;
    $address_soi = isset($_POST['address_soi']) ? $mysqli->real_escape_string(trim($_POST['address_soi'])) : null;
    $province_id = isset($_POST['province_id']) ? $mysqli->real_escape_string(trim($_POST['province_id'])) : null;
    $district_id = isset($_POST['district_id']) ? $mysqli->real_escape_string(trim($_POST['district_id'])) : null;
    $zipcode = isset($_POST['zipcode']) ? $mysqli->real_escape_string(trim($_POST['zipcode'])) : null;
    $mobile = isset($_POST['mobile']) ? $mysqli->real_escape_string(trim($_POST['mobile'])) : null;
    $email = isset($_POST['zipcode']) ? $mysqli->real_escape_string(trim($_POST['email'])) : null;
    $amphur_id = isset($_POST['amphur_id']) ? $mysqli->real_escape_string(trim($_POST['amphur_id'])) : null;
    $type = isset($_POST['type']) ? $mysqli->real_escape_string(trim($_POST['type'])) : null;
    $member_id = isset($_POST['member_no']) ? $mysqli->real_escape_string(trim($_POST['member_no'])) : null;
    $uid = isset($_POST['uid']) ? $mysqli->real_escape_string(trim($_POST['uid'])) : null;
    $array = array(
        "member_id" => $member_id,
        "address_no" => $address_no,
        "address_moo" => $address_moo,
        "address_village" => $address_village,
        "province_id" => $province_id,
        "zipcode" => $zipcode,
        "mobile" => $mobile,
        "email" => $email,
        "amphur_id" => $amphur_id,
        "address_soi" => $address_soi,
    );
    $data = array();
    $arrayNew=array();
    if($type == 'select'){
        $sql = "SELECT 
        tb1.member_id,tb1.firstname_th,tb1.lastname_th,tb1.sex,tb1.member_pic,tb1.birthday,tb1.member_date,tb1.mobile,tb1.email,tb1.id_card,tb1.position,tb1.salary,tb1.other_income,tb1.tel,
        tb1.c_address_no,tb1.c_address_moo,tb1.c_address_village,tb1.c_address_road,tb1.c_address_soi,tb1.c_district_id,tb1.c_amphur_id,tb1.c_province_id,tb1.c_zipcode,
        tb1.address_no,tb1.address_moo,tb1.address_village,tb1.address_road,tb1.address_soi,tb1.district_id,tb1.amphur_id,tb1.province_id,tb1.zipcode,
        tb2.prename_short,
        tb3.district_name,tb7.district_name AS i_district_name,
        tb4.amphur_name,tb8.amphur_name AS i_amphur_name,
        tb5.province_name,tb9.province_name AS i_province_name,
        tb6.mem_type_name,
        tb10.mem_group_name AS Affiliation_1,
        tb11.mem_group_name AS Affiliation_2,
        tb12.mem_group_name AS Affiliation_3
        FROM coop_mem_apply AS tb1
        LEFT JOIN coop_prename AS tb2 ON tb2.prename_id = tb1.prename_id
        LEFT JOIN coop_district AS tb3 ON tb3.district_id = tb1.c_district_id
        LEFT JOIN coop_amphur AS tb4 ON tb4.amphur_id = tb1.c_amphur_id
        LEFT JOIN coop_province AS tb5 ON tb5.province_id = tb1.c_province_id
        LEFT JOIN coop_mem_type AS tb6 ON tb6.mem_type_id = tb1.mem_type_id
        LEFT JOIN coop_district AS tb7 ON tb7.district_id = tb1.district_id
        LEFT JOIN coop_amphur AS tb8 ON tb8.amphur_id = tb1.amphur_id
        LEFT JOIN coop_province AS tb9 ON tb9.province_id = tb1.province_id
        LEFT JOIN coop_mem_group AS tb10 ON tb10.id = tb1.department
        LEFT JOIN coop_mem_group AS tb11 ON tb11.id = tb1.faction
        LEFT JOIN coop_mem_group AS tb12 ON tb12.id = tb1.level
        WHERE member_id = '{$member_id}'";
        $rs = $mysqli->query($sql);
        if ( $rs->num_rows ) {
            $data['status'] = 1;
            $row = $rs->fetch_assoc();
            $user_img = '';
            if ( $row['member_pic'] == '' ) {
                //$user_img = ( $row['sex'] == 'F' ) ? ONLINE_URL.'APIs/img/user_female.jpg' : ONLINE_URL.'APIs/img/user_male.jpg';
                $user_img = ( $row['sex'] == 'F' ) ? ONLINE_URL.'APIs/img/female.png' : ONLINE_URL.'APIs/img/male.png';
            } else {
                $user_img = ONLINE_URL.'assets/uploads/members/'.$row['member_pic'];
            }
    
            $no = $row['c_address_no'];
            $village = ' '.$row['c_address_village'];
            $moo = ( $row['c_address_moo'] == '' ) ? '' : ' '.$row['c_address_moo'];
            $road = ( $row['c_address_road'] == '' ) ? '' : ' '.$row['c_address_road'];
            $soi = ( $row['c_address_soi'] == '' ) ? '' : ' '.$row['c_address_soi'];
            $district = '';
            if ( $row['c_province_id'] == 1 ) {
                $district = ' '.$row['district_name'];
                $amphur = ' '.$row['amphur_name'];
                $province = ' '.$row['province_name'];
            } else {
                $district = ( $row['district_name'] == '' ) ? '' : ''.$row['district_name'];
                $amphur = ( $row['amphur_name'] == '' ) ? '' : ''.$row['amphur_name'];
                $province = ( $row['province_name'] == '' ) ? '' : ''.$row['province_name'];
            }
            $zipcode = ' '.$row['c_zipcode'];
    
            // --------------------------------------------
    
            $i_no = $row['address_no'];
            $i_village = ' '.$row['address_village'];
            $i_moo = ( $row['address_moo'] == '' ) ? '' : ' '.$row['address_moo'];
            $i_road = ( $row['address_road'] == '' ) ? '' : ' '.$row['address_road'];
            $i_soi = ( $row['address_soi'] == '' ) ? '' : ' '.$row['address_soi'];
            $i_district = '';
            if ( $row['province_id'] == 1 ) {
                $i_district = ' '.$row['i_district_name'];
                $i_amphur = ' '.$row['i_amphur_name'];
                $i_province = ' '.$row['i_province_name'];
            } else {
                $i_district = ( $row['i_district_name'] == '' ) ? '' : ''.$row['i_district_name'];
                $i_amphur = ( $row['i_amphur_name'] == '' ) ? '' : ''.$row['i_amphur_name'];
                $i_province = ( $row['i_province_name'] == '' ) ? '' : ''.$row['i_province_name'];
            }
            $i_zipcode = ' '.$row['zipcode'];
    
            $affiliation_1 = ($row['Affiliation_1'] == '' || $row['Affiliation_1'] == 'ไม่ระบุ' || $row['Affiliation_1'] == null) ? '' : $row['Affiliation_1'];
            $affiliation_2 = ($row['Affiliation_2'] == '' || $row['Affiliation_2'] == 'ไม่ระบุ' || $row['Affiliation_2'] == null) ? '' : $row['Affiliation_2'];
            $affiliation_3 = ($row['Affiliation_3'] == '' || $row['Affiliation_3'] == 'ไม่ระบุ' || $row['Affiliation_3'] == null) ? '' : $row['Affiliation_3'];
    
            $comma_1 =  '';
            if ( $affiliation_1 != '' && $affiliation_2 != '' ) $comma_1 = ', ';
    
            $comma_2 =  '';
            if ( $affiliation_2 != '' && $affiliation_3 != '' ) $comma_2 = ', ';
            if ( $affiliation_1 != '' && $affiliation_2 == '' && $affiliation_3 != '' ) $comma_2 = ', ';
    
            $data['data'] = [
                'user_img' => $user_img,
                'member_id' => $row['member_id'],
                'member_name' => $row['prename_short']." ".$row['firstname_th']." ".$row['lastname_th'],
                'mem_type_name' => $row['mem_type_name'],
                'position' => $row['position'] != '' ?  $row['position'] : '-' ,
                'salary' => ( $row['salary'] > 0 ) ? number_format($row['salary'], 2) : "0.00",
                'other_income' => ( $row['other_income'] > 0 ) ? number_format($row['other_income'], 2) : "0.00",
                'id_card' => $row['id_card'],
                'birthday' => dateDB2thaidate($row['birthday'],true,false,false),
                'age' => calcDate($row['birthday']),
                'member_date' => dateDB2thaidate($row['member_date'],true,false,false),
                'registerDate' => calcDate($row['member_date']),
                'email' => $row['email'] != '' ? $row['email'] : '-' ,
                'tel' =>  $row['tel'] != '' ? $row['tel'] : '-',
                'mobile' => $row['mobile'] != '' ? $row['mobile'] : '-',
                'address' => $no.$moo.$village.$road.$soi.$district.$amphur.$province.$zipcode,
                'i_address' => $i_no.$i_moo.$i_village.$i_road.$i_soi.$i_district.$i_amphur.$i_province.$i_zipcode,
                'affiliation' => $affiliation_1.$comma_1.$affiliation_2.$comma_2.$affiliation_3,
                'district_id' => $row['district_id'],
                'amphur_id' => $row['amphur_id'],
                'province_id' => $row['province_id'],
                'address_no' => $i_no,
                    'address_moo' => $i_moo,
                    'address_village' => $i_village,
                    'address_road' => $i_road,
                    'address_soi' => $i_soi,
                    'district' => $i_district,
                    'amphur' => $i_amphur,
                    'province' => $i_province,
                    'zipcode' => $i_zipcode,
                'rcv_desc' => '-',
            ];
        }
}else{

    $data['array'] = $array;

    foreach ($array as $key => $value){
        // echo $key.' ------  '.$value;
        $data['key'] = $key;
        $sql = "SELECT {$key} FROM coop_mem_apply  WHERE  member_id = '{$member_id}'";
        $rs = $mysqli->query($sql);
        $row = $rs->fetch_assoc();
        if($row[$key] != $value){
            $data['dddd'] = $value;
            $data['aaa'] = $row[$key];
            $data['key1'] = $key;
            $arrayNew = array_merge( $arrayNew, array( $key => $value.' '.$row[$key] ) );
            $sqlInsert = "INSERT INTO coop_member_data_history (member_id,label_name,input_name,old_value,new_value,user_id,created_at,platform,uid)
           VALUES('{$member_id}','{$key}','{$key}','{$row[$key]}','{$value}','{$member_id}',NOW(),'{$platform}','{$uid}')";
           $data['sqlInsert'] = $sqlInsert;
           if ( $mysqli->query($sqlInsert) === TRUE ) {
            $data['status'] = 1;
            $sqlUpdate = "UPDATE coop_mem_apply SET $key ='{$value}', update_time = NOW(),update_ip = '{$ip}' WHERE  member_id = '{$member_id}'";
            $data['sqlUpdate'] = $sqlUpdate;
            if ( $mysqli->query($sqlUpdate) === TRUE ) {
                            $data['status'] = 1;
                            // $data['account_id'] = $mysqli->insert_id;
                        }
           }
          
            //push array
        }else{
            //noypush
        }

    }
    $data['sql1'] = $sql;
    $data['arrayNew'] = $arrayNew;

}
    
    echo json_encode($data); 
    exit();
?>