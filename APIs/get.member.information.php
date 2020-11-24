<?php
    require_once("config.inc.php");

    $data = [ 'status' => 0, 'responseText' => '' ];

    require_once("parameter.inc.php");
    require_once("token.validate.php");
    
    $sql = "SELECT 
    tb1.member_id,tb1.firstname_th,tb1.lastname_th,tb1.sex,tb1.member_pic,tb1.birthday,tb1.member_date,tb1.mobile,tb1.email,
    tb1.c_address_no,tb1.c_address_moo,tb1.c_address_village,tb1.c_address_road,tb1.c_address_soi,tb1.c_province_id,tb1.c_zipcode,
    tb2.prename_short,
    tb3.district_name,
    tb4.amphur_name,
    tb5.province_name
    FROM coop_mem_apply AS tb1
    LEFT JOIN coop_prename AS tb2 ON tb2.prename_id = tb1.prename_id
    LEFT JOIN coop_district AS tb3 ON tb3.district_id = tb1.c_district_id
    LEFT JOIN coop_amphur AS tb4 ON tb4.amphur_id = tb1.c_amphur_id
    LEFT JOIN coop_province AS tb5 ON tb5.province_id = tb1.c_province_id
    WHERE member_id = '{$member_id}'";
    $rs = $mysqli->query($sql);
    if ( $rs->num_rows ) {
        $data['status'] = 1;
        $row = $rs->fetch_assoc();
        $user_img = '';
        if ( $row['member_pic'] == '' ) {
            $user_img = ( $row['sex'] == 'F' ) ? ONLINE_URL.'APIs/img/user_female.jpg' : ONLINE_URL.'APIs/img/user_male.jpg';
        } else {
            $user_img = SYSTEM_URL.'assets/uploads/members/'.$row['member_pic'];
        }

        $no = $row['c_address_no'];
        $village = ' '.$row['c_address_village'];
        $moo = ( $row['c_address_moo'] == '' ) ? '' : ' ม.'.$row['c_address_moo'];
        $road = ( $row['c_address_road'] == '' ) ? '' : ' ถ.'.$row['c_address_road'];
        $soi = ( $row['c_address_soi'] == '' ) ? '' : ' ซ.'.$row['c_address_soi'];
        $district = '';
        if ( $row['c_province_id'] == 1 ) {
            $district = ' '.$row['district_name'];
            $amphur = ' '.$row['amphur_name'];
            $province = ' '.$row['province_name'];
        } else {
            $district = ( $row['district_name'] == '' ) ? '' : ' ต.'.$row['district_name'];
            $amphur = ( $row['amphur_name'] == '' ) ? '' : ' อ.'.$row['amphur_name'];
            $province = ( $row['province_name'] == '' ) ? '' : ' จ.'.$row['province_name'];
        }
        $zipcode = ' '.$row['c_zipcode'];

        $data['data'] = [
            'user_img' => $user_img,
            'member_id' => $row['member_id'],
            'member_name' => $row['prename_short']." ".$row['firstname_th']." ".$row['lastname_th'],
            'age' => calcDate($row['birthday']),
            'member_date' => dateDB2thaidate($row['member_date'],true,false,false),
            'registerDate' => calcDate($row['member_date']),
            'email' => $row['email'],
            'mobile' => $row['mobile'],
            'address' => $no.$moo.$village.$road.$soi.$district.$amphur.$province.$zipcode
        ];
    }

    echo json_encode($data);
    exit();
?>