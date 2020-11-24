<?php
    require_once("../config.inc.php");
    require_once("../token.validate.php");

    // $data = [ 'status' => 0, 'responseText' => '' ];



    $month = isset($_POST['month']) ? $mysqli->real_escape_string($_POST['month']) : null;
    $year = isset($_POST['year']) ? $mysqli->real_escape_string($_POST['year']) : null;
    // $month = (int)$month;
    $year = ((int)$year + 543);

    // $month = date('m', strtotime(date('Y-m-d')." -1 month"));

    $total = 0;
    
    // -------------------- Get List--------------------
    $sql = "SELECT
    tb1.pay_amount,
    tb2.profile_year,
    tb2.profile_month,
    tb1.member_id,
    IF(tb3.deduct_id = 15,CONCAT(tb3.deduct_detail,' (หักเข้าบัญชีเงินฝาก)'),tb3.deduct_detail) AS deduct_detail
    ,SUM(tb1.pay_amount) AS pay_amount
    FROM coop_finance_month_detail AS tb1
    INNER JOIN coop_finance_month_profile AS tb2 ON tb1.profile_id = tb2.profile_id
    LEFT JOIN coop_deduct AS tb3 ON tb1.deduct_id = tb3.deduct_id
    WHERE tb1.member_id = '{$member_id}' AND tb2.profile_year = '{$year}'
    GROUP BY tb2.profile_month,tb2.profile_year
    ORDER BY tb3.deduct_id ASC";
    //$data['sql'] = $sql;
    $rs = $mysqli->query($sql);
    if ( $rs->num_rows ) {
        $data['status'] = 1;
        // $data['month'] = getMonths((int)$month);
        $data['year'] = (string)$year;
        while( ($row = $rs->fetch_assoc()) ){
            $total = $total += $row['pay_amount'];
            $data['data'][] = [
                'deduct_detail' => $row['deduct_detail'],
                'pay_amount' => number_format($row['pay_amount'], 2),
                'year' => $row['profile_year'],
                'month' => getMonths($row['profile_month']),
                'monthText' => DateThai($row['profile_month']),
                'monthnum' => $row['profile_month'],
            ];
        }
        $data['total'] = number_format($total, 2);
        $data['txt_total'] = NumberToChar(number_format($total, 2));
    }
    // -------------------- Get List--------------------

    echo json_encode($data); 
    exit();


    
?>

<?php
	// function DateThai($strDate)
	// {

	// 	// $strYear = date("Y",strtotime($strDate))+543;
	// 	$strMonth= $strDate;
	// 	// $strDay= date("j",strtotime($strDate));
	// 	// $strHour= date("H",strtotime($strDate));
	// 	// $strMinute= date("i",strtotime($strDate));
	// 	// $strSeconds= date("s",strtotime($strDate));
	// 	$strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
	// 	$strMonthThai=$strMonthCut[$strMonth];
	// 	return "$strMonthThai";
	// // }

	// $strDate = "2008-08-14 13:42:44";
	// echo "ThaiCreate.Com Time now : ".DateThai($strDate);
?>
