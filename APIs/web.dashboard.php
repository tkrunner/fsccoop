<?php

    require_once("config.inc.php");
    require_once("token.validate.php");

    $data = [ 'status' => 0, 'responseText' => '', 'is_response_gain' => '', 'is_response_diviend' => '' ];

    $sql = "SELECT 
    tb1.member_pic, tb1.sex, tb1.member_id, CONCAT(tb2.prename_short, ' ', tb1.firstname_th, '  ', tb1.lastname_th) AS member_name, tb1.birthday, tb1.id_card, tb1.member_date, 
    tb1.tel, tb1.mobile, tb1.email, tb1.position,
    FORMAT(tb1.salary, 2) AS salary,
    FORMAT(tb1.other_income, 2) AS other_income,
    CASE tb1.member_status
        WHEN 1 THEN 'ปกติ'
        WHEN 2 THEN 'ลาออก'
        WHEN 3 THEN 'รออนุมัติ'
        ELSE 'ไม่ระบุ'
    END AS  member_status,
    CONCAT(
        IF(tb1.address_no != null OR tb1.address_no != '', CONCAT('เลขที่ ', tb1.address_no), ''),
        IF(tb1.address_moo != null OR tb1.address_moo != '', CONCAT(' ม.', tb1.address_moo), ''),
        IF(tb1.address_village != null OR tb1.address_village != '', CONCAT(' ', tb1.address_village), ''),
        IF(tb1.address_road != null OR tb1.address_road != '', CONCAT(' ถ.', tb1.address_road), ''),
        IF(tb1.address_soi != null OR tb1.address_soi != '', CONCAT(' ซ.', tb1.address_soi), ''),
        IF(tb1.district_id != null OR tb1.district_id != '', CONCAT(IF( tb1.province_id = 1, ' แขวง', ' ต.'), tb3.district_name), ''),
        IF(tb1.amphur_id != null OR tb1.amphur_id != '', CONCAT(IF( tb1.province_id = 1, ' เขต', ' อ.'), tb4.amphur_name), ''),
        IF(tb1.province_id != null OR tb1.province_id != '', CONCAT(IF( tb1.province_id = 1, ' ', ' จ.'), tb5.province_name), ''),
        IF(tb1.zipcode != null OR tb1.zipcode != '', CONCAT(' ', tb1.zipcode), '')
    ) AS idcard_address,
    CONCAT(
        IF(tb1.c_address_no != null OR tb1.c_address_no != '', CONCAT('เลขที่ ', tb1.c_address_no), ''),
        IF(tb1.c_address_moo != null OR tb1.c_address_moo != '', CONCAT(' ม.', tb1.c_address_moo), ''),
        IF(tb1.c_address_village != null OR tb1.c_address_village != '', CONCAT(' ', tb1.c_address_village), ''),
        IF(tb1.c_address_road != null OR tb1.c_address_road != '', CONCAT(' ถ.',tb1.c_address_road), ''),
        IF(tb1.c_address_soi != null OR tb1.c_address_soi != '', CONCAT(' ซ.', tb1.c_address_soi), ''),
        IF(tb1.c_district_id != null OR tb1.c_district_id != '', CONCAT(IF( tb1.c_province_id = 1, ' แขวง', ' ต.'), tb6.district_name), ''),
        IF(tb1.c_amphur_id != null OR tb1.c_amphur_id != '', CONCAT(IF( tb1.c_province_id = 1, ' เขต', ' อ.'), tb7.amphur_name), ''),
        IF(tb1.c_province_id != null OR tb1.c_province_id != '', CONCAT(IF( tb1.c_province_id = 1, ' ', ' จ.'), tb8.province_name), ''),
        IF(tb1.c_zipcode != null OR tb1.c_zipcode != '', CONCAT(' ', tb1.c_zipcode), '')
    ) AS current_address,
    tb1.c_address_no,
    tb1.c_address_moo,
    tb1.c_address_village,
    tb1.c_address_road,
    tb1.c_address_soi,
    tb1.c_district_id,
    tb1.c_amphur_id,
    tb1.c_province_id,
    tb1.c_zipcode

    FROM coop_mem_apply AS tb1
    LEFT JOIN coop_prename AS tb2 ON tb2.prename_id = tb1.prename_id
    LEFT JOIN coop_district AS tb3 ON tb3.district_id = tb1.district_id
    LEFT JOIN coop_amphur AS tb4 ON tb4.amphur_id = tb1.amphur_id
    LEFT JOIN coop_province AS tb5 ON tb5.province_id = tb1.province_id
    LEFT JOIN coop_district AS tb6 ON tb6.district_id = tb1.c_district_id
    LEFT JOIN coop_amphur AS tb7 ON tb7.amphur_id = tb1.c_amphur_id
    LEFT JOIN coop_province AS tb8 ON tb8.province_id = tb1.c_province_id
    WHERE tb1.member_id = '{$member_id}'";
    $rs = $mysqli->query($sql);
    if ( $rs->num_rows ) {
        $row = $rs->fetch_assoc();
        $data['status'] = 1;
        if ( $row['member_pic'] == '' ) {
            $user_img = ( $row['sex'] == 'F' ) ? 'https://dev.policehospital-coop.com/assets/uploads/members/default.png' : 'https://dev.policehospital-coop.com/assets/uploads/members/default.png' ;
        } else {
            $user_img = ONLINE_URL.'assets/uploads/members/'.$row['member_pic'];
        }
        $data['data'] = [
            'user_img'          =>  $user_img,
            'member_id'         =>  $row['member_id'],
            'member_name'       =>  $row['member_name'],
            'affiliation'       =>  '-',
            'member_type'       =>  $row['member_status'],
            'birthday'          =>  dateDB2thaidate($row['birthday'], true, false, false),
            'age'               =>  calcDate($row['birthday']),
            'id_card'           =>  $row['id_card'],
            'position'          =>  ($row['position'] == '' OR $row['position'] == null) ? '-' : $row['position'],
            'salary'            =>  $row['salary'],
            'other_income'      =>  $row['other_income'],
            'member_date'       =>  dateDB2thaidate($row['member_date'], true, false, false),
            'registerDate'      =>  calcDate($row['member_date']),
            'address_idcard'    =>  ($row['idcard_address']) == '' ? '-' : $row['idcard_address'],
            'address_contract'  =>  ($row['current_address']) == '' ? '-' : $row['current_address'],
            'tel'               =>  ($row['tel'] == '' OR $row['tel'] == null) ? '-' : $row['tel'],
            'mobile'            =>  ($row['mobile'] == '' OR $row['mobile'] == null) ? '-' : $row['mobile'],
            'email'             =>  ($row['email'] == '' OR $row['email'] == null) ? '-' : $row['email'],
            'address_current_update'    => [
                'address_no' => $row['c_address_no'],
                'address_moo' => $row['c_address_moo'],
                'address_village' => $row['c_address_village'],
                'address_road' => $row['c_address_road'],
                'address_soi' => $row['c_address_soi'],
                'district_id' => ($row['c_district_id']) ? $row['c_district_id'] : 0,
                'amphur_id' => ($row['c_amphur_id']) ? $row['c_amphur_id'] : 0,
                'province_id' => ($row['c_province_id']) ? $row['c_province_id'] : 0,
                'zipcode' => $row['c_zipcode']
            ]
        ];
    }

    echo json_encode($data); 
    exit();
?>