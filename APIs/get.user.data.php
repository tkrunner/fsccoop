<?php
    require_once("config.inc.php");

    $data = [ 'status' => 1, 'responseText' => '', 'is_icon' => 0, 'background_img' => ONLINE_URL.'APIs/img/background_img.png' ];

    //$member_id = '000001';
    $member_id = isset($_POST['mid']) ? $mysqli->real_escape_string($_POST['mid']) : null;
    require_once("parameter.inc.php");
    require_once("token.validate.php");

    $sql = "SELECT firstname_th,lastname_th,sex,member_pic FROM coop_mem_apply WHERE member_id = '{$member_id}'";
    $rs = $mysqli->query($sql);
    if ( $rs->num_rows ) {
        $row = $rs->fetch_assoc();
        $user_img = '';
        if ( $row['member_pic'] == '' ) {
            $user_img = ( $row['sex'] == 'F' ) ? ONLINE_URL.'APIs/img/female.png' : ONLINE_URL.'APIs/img/male.png';
        } else {
            $user_img = SYSTEM_URL.'assets/uploads/members/'.$row['member_pic'];
        }
        $data['member_name'] = 'คุณ'.$row['firstname_th'].' '.$row['lastname_th'];
        $data['user_img'] = $user_img;

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
                $district = ( $row['district_name'] == '' ) ? '' : ' ต.'.$row['district_name'];
                $amphur = ( $row['amphur_name'] == '' ) ? '' : ' อ.'.$row['amphur_name'];
                $province = ( $row['province_name'] == '' ) ? '' : ' จ.'.$row['province_name'];
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
                $i_district = ( $row['i_district_name'] == '' ) ? '' : ' ต.'.$row['i_district_name'];
                $i_amphur = ( $row['i_amphur_name'] == '' ) ? '' : ' อ.'.$row['i_amphur_name'];
                $i_province = ( $row['i_province_name'] == '' ) ? '' : ' จ.'.$row['i_province_name'];
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
                'member_date' => calcDate($row['member_date'],true,false,false),
                'registerDate' => dateDB2thaidate($row['member_date'],true,false,false),
                'email' => $row['email'] != '' ? $row['email'] : '-' ,
                'tel' =>  $row['tel'] != '' ? $row['tel'] : '-',
                'mobile' => $row['mobile'] != '' ? $row['mobile'] : '-',
                'i_address' => $no.$moo.$village.$road.$soi.$district.$amphur.$province.$zipcode,
                'address' => $i_no.$i_moo.$i_village.$i_road.$i_soi.$i_district.$i_amphur.$i_province.$i_zipcode,
                'affiliation' => $affiliation_1.$comma_1.$affiliation_2.$comma_2.$affiliation_3,
                'district_id' => $row['district_id'],
                'amphur_id' => $row['amphur_id'],
                'province_id' => $row['province_id'],
                'rcv_desc' => '-',
                'edit_data' => [
                    'address_no' => $row['c_address_no'],
                    'address_moo' => $row['c_address_moo'],
                    'address_village' => $row['c_address_village'],
                    'address_road' => $row['c_address_road'],
                    'address_soi' => $row['c_address_soi'],
                    'district_id' => $row['c_district_id'],
                    'amphur_id' => $row['c_amphur_id'],
                    'province_id' => $row['c_province_id'],
                    'zipcode' => $row['c_zipcode']
                ]
            ];
    
            $sql = "SELECT CONCAT( tb3.prename_short,' ',tb1.g_firstname,' ',tb1.g_lastname ) AS gain_name,tb2.relation_name,
            CASE 
                WHEN tb1.g_mobile = '' THEN IF(tb1.g_tel = '',tb1.g_office_tel,tb1.g_tel)
                ELSE tb1.g_mobile
            END AS gain_mobile,
            CONCAT( tb1.g_share_rate, '%' ) AS gain_percent
            FROM coop_mem_gain_detail AS tb1
            LEFT JOIN coop_mem_relation AS tb2 ON tb1.g_relation_id = tb2.relation_id
            LEFT JOIN coop_prename AS tb3 ON tb1.g_prename_id = tb3.prename_id
            WHERE tb1.member_id = '{$member_id}' ORDER BY g_create ASC";
            $rs = $mysqli->query($sql);
            if ( $rs->num_rows ) {
                while( ($row = $rs->fetch_assoc()) ){
                    $data['gain'][] = $row;
                }
            } else {
                $data['is_response_gain'] = 'ไม่พบผู้รับผลประโยชน์';
            }
    
            $sql = "SELECT year FROM coop_dividend_average WHERE member_id = '{$member_id}' ORDER BY year DESC";
            $rs = $mysqli->query($sql);
            if ( $rs->num_rows ) {
                while(( $row = $rs->fetch_assoc() )) {
                    $data['diviend'][] = [
                        'year'  =>  (string)($row['year'] + 543),
                        'yearText'  =>  'ปี '.(string)($row['year'] + 543)
                    ];
                }
            } else {
                $data['is_response_diviend'] = 'ไม่พบเงินปันผลเฉลี่ยคืน';
            }
    
        } else {
            $data['responseText'] = 'ไม่พบข้อมูลสมาชิก';
        }
    

        //$data['share_collect'] = '0.00';
		// $sql = "SELECT share_collect_value FROM coop_mem_share WHERE trim(member_id) = trim('{$member_id}') AND share_status IN('1','2') ORDER BY  share_date DESC LIMIT 1";
		// $rs = $mysqli->query($sql);
		// if ( $rs->num_rows ) {
		// 	$row = $rs->fetch_assoc();
		// 	$data['share_collect'] = ( $row['share_collect_value'] > 0 ) ? number_format($row['share_collect_value'], 2) : number_format(0, 2);
		// } else {
		// 	$data['share_collect'] = number_format(0, 2);
		// }

        // $deposit_balance = 0;
        // $sql = "SELECT account_id FROM coop_maco_account WHERE trim(mem_id) = trim('{$member_id}') AND trim(account_status) = '0'";
        // $rs = $mysqli->query($sql);
        // if ( $rs->num_rows ) {
        //     while( ($row = $rs->fetch_assoc()) ){
        //         $account_id = $row['account_id'];
        //         $sqlAcc = "SELECT transaction_balance FROM coop_account_transaction WHERE trim(account_id) = trim('$account_id') ORDER BY transaction_time DESC, transaction_id ASC";
        //         $rsAcc = $mysqli->query($sqlAcc);
        //         $rowAcc = $rsAcc->fetch_assoc();
        //         $deposit_balance += $rowAcc['transaction_balance'];
        //     }
        // }
        
        // //$data['deposit_sum'] = '0.00';
        // $data['deposit_sum'] = ( $deposit_balance > 0 ) ? number_format($deposit_balance, 2) : number_format(0, 2);

        // $sql = "SELECT tb2.transaction_balance FROM coop_maco_account AS tb1 
        // INNER JOIN coop_account_transaction AS tb2 ON tb1.account_id = tb2.account_id
        // WHERE trim(tb1.mem_id) = trim('{$member_id}')
        // ORDER BY tb2.transaction_id DESC LIMIT 1";
        // $rs = $mysqli->query($sql);
        // if ( $rs->num_rows ) {
        //     $row = $rs->fetch_assoc();
        //     $data['deposit_sum'] = ( $row['transaction_balance'] > 0 ) ? number_format($row['transaction_balance'], 2) : number_format(0, 2);
        // } else {
        //     $data['deposit_sum'] = number_format(0, 2);
        // }
    }


    echo json_encode($data); 
    exit();
?>