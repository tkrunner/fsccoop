<?php
    require_once("../config.inc.php");

    $data = [ 'status' => 0, 'responseText' => '' ];

    require_once("../parameter.inc.php");
    require_once("../token.validate.php");

    // $sql = "SELECT year FROM coop_dividend_average WHERE trim(member_id) = '{$member_id}' ORDER BY year DESC";
    // $rs = $mysqli->query($sql);
    // if ( $rs->num_rows ) {
    //     $data['status'] = 1;
    //     while(( $row = $rs->fetch_assoc() )) {
    //         $data['diviend'][] = [
    //             'year'  =>  (string)($row['year'] + 543),
    //             'yearText'  =>  'ปี '.(string)($row['year'] + 543)
    //         ];
    //     }
    // } 

    //$sql = "SELECT `year`, dividend_value, average_return_value, gift_varchar
    //FROM coop_dividend_average
    //WHERE trim(member_id) = trim('{$member_id}')
    //ORDER BY `year` DESC";
	
	$sql = "SELECT
			t1.master_id,
			t1.year,
			t1.dividend_value,
			t1.average_return_value,
			t1.gift_varchar,
			t1.share_value,
			(SELECT amount FROM coop_dividend_deduct WHERE t1.member_id = member_id AND t1.master_id = master_id AND deduct_id = 1) AS deduct_type_1,
			(SELECT amount FROM coop_dividend_deduct WHERE t1.member_id = member_id AND t1.master_id = master_id AND deduct_id = 2) AS deduct_type_2
		FROM
			coop_dividend_average AS t1
		WHERE
			t1.member_id = '{$member_id}'
		ORDER BY
			t1.year DESC LIMIT 0,5";
    $rs = $mysqli->query($sql);
    if ( $rs->num_rows ) {
        $data['status'] = 1;
        while(( $row = $rs->fetch_assoc() )) {
            $data['data'][] = [
                'year' 	=> ($row['year']+543), // ปีที่คำนวณเงินปันผล (ปี พ.ศ.)
                'dividend' 	=> number_format($row['dividend_value'], 2), // เงินปันผล
                'dividend_avg' => number_format($row['average_return_value'], 2), // เงินเฉลี่ยคืน
                'gift_varchar' => number_format($row['gift_varchar'], 2), // ของชำร่วย
                'deduct_type_1' => number_format($row['deduct_type_1'], 2), // หักชำระค่าฌาปนกิจ
                'share_value' => number_format($row['share_value'], 2), // หุ้นสิ้นปี
                'deduct_type_2' => number_format($row['deduct_type_2'], 2), // หักชำระเงินกู้ประเภทเฉพาะกิจ
                'dividend_sum' => number_format($row['dividend_value'] + $row['average_return_value'] + $row['gift_varchar'] - $row['deduct_type_1'] -$row['deduct_type_2'] , 2), // รวมสุทธิ
                'dividend_per' => number_format(($row['average_return_value'] * 10)/100), // ปันผลเปอเซ็น
                'dividend_avg_per' => number_format(($row['dividend_value']*10)/100), // เฉลี่ยคืนเปอเซ็น
            ];
        }
    }else{
        $data['respontext'] = 'ไม่พบรายการปันผล-เฉลี่ยคืน';
    }
    if($data == null){
        $data['respontext'] = 'ไม่พบรายการปันผล-เฉลี่ยคืน';
    }
    echo json_encode($data);
    exit();
?>
