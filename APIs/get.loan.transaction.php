<?php
    require_once("config.inc.php");
    require_once("token.validate.php");

    $data = [ 'responseText' => '', 'guarantee' => 0 , "guaranteeData" => [] , 'transaction' => 0 , "transactionData" => [] ];

    $loan_id = isset($_POST['loan_id']) ? $mysqli->real_escape_string($_POST['loan_id']) : null;

    $sql = "SELECT 
    tb1.id, 
    tb1.approve_date,
    IF(tb1.contract_number = '', 'N/A', tb1.contract_number) AS contract_number, 
    FORMAT(tb1.loan_amount, 2) AS loan_amount, 
    FORMAT(tb1.loan_amount_balance, 2) AS loan_amount_balance, 
    tb1.period_amount,
    tb1.loan_status,
    IF(tb1.loan_status = 1, 'ปกติ', 'เบี้ยวหนี้') AS loan_status_desc,
    tb1.pay_type,
    IF(tb1.pay_type = '1', (SELECT FORMAT(principal_payment, 2) FROM coop_loan_period WHERE loan_id = tb1.id AND date_count = '31' LIMIT 0 , 1 ) 
		, (SELECT FORMAT(total_paid_per_month, 2) FROM coop_loan_period WHERE loan_id = tb1.id LIMIT 1)) AS pay_per_month,
	tb3.loan_type,
	tb1.money_per_period
	
    FROM coop_loan AS tb1
    INNER JOIN coop_loan_name AS tb2 ON tb1.loan_type = tb2.loan_name_id
    INNER JOIN coop_loan_type AS tb3 ON tb2.loan_type_id = tb3.id
    WHERE tb1.member_id = '{$member_id}' AND loan_status = '1' AND tb1.id = {$loan_id}
    OR tb1.member_id = '{$member_id}' AND loan_status = '6' AND tb1.id = {$loan_id}
    ORDER BY createdatetime DESC";
    $rs = $mysqli->query($sql);
    if ( $rs->num_rows ) {
        while( ($row = $rs->fetch_assoc()) ){
            $data['data'] = [
                'id' => $row['id'],
                'approve_date' => ($row['approve_date'] == null) ? 'N/A' : dateDB2thaidate($row['approve_date'],true,false,false),
                'contract_number' => $row['contract_number'],
                'loan_amount' => $row['loan_amount'],
                'loan_amount_balance' => $row['loan_amount_balance'],
                'period_amount' => ($row['period_amount'] == null OR $row['period_amount'] == 0) ? 'N/A' : $row['period_amount'],
                'loan_status' => $row['loan_status'],
                'loan_status_desc' => $row['loan_status_desc'],
                'pay_type' => $row['pay_type'],
                'pay_per_month' =>  ($row['pay_per_month'] == 0  ? number_format($row["money_per_period"] , 2 , "." , ",") : $row['pay_per_month'])   ,
				'loan_type' => $row['loan_type']
            ];
        }
    }

    $sql = "SELECT tb2.firstname_th,tb2.lastname_th, tb3.prename_short FROM coop_loan_guarantee_person AS tb1
    INNER JOIN coop_mem_apply AS tb2 ON tb1.guarantee_person_id = tb2.member_id
    INNER JOIN coop_prename AS tb3 ON tb2.prename_id = tb3.prename_id
    WHERE tb1.loan_id = {$loan_id} ORDER BY tb2.member_id ASC";
    $rs = $mysqli->query($sql);
    if ( $rs->num_rows ) {
        $data['guarantee'] = 1;
        while( ($row = $rs->fetch_assoc()) ){
            $data['guaranteeData'][] = [ 
                'name' => $row['prename_short']." ".$row['firstname_th']." ".$row['lastname_th'] 
            ];
        }
    }
	
	
	/*
	$this->db->from('coop_loan as t1');
	$this->db->join('coop_loan_name as t3','t1.loan_type = t3.loan_name_id','inner');
	$this->db->join('coop_loan_type as t5','t3.loan_type_id = t5.id','inner');
	$this->db->join('coop_user as t2','t1.admin_id = t2.user_id','left');
	$this->db->join('coop_loan_transfer as t4',"t1.id = t4.loan_id AND t4.transfer_status != '2'",'left');
	$this->db->join('coop_loan_guarantee_compromise as t6', "t1.id = t6.loan_id", "left");
	$this->db->join('coop_loan_compromise as t7', "t6.compromise_id = t7.id", "left");
	$this->db->join('coop_mem_apply as t8', "t7.member_id = t8.member_id", "left");
	$this->db->join('coop_prename as t9', "t8.prename_id = t9.prename_id", "left");
	$this->db->where("t1.member_id = '".$member_id."' ");
	$this->db->order_by("t1.id DESC");
	*/
	
   
	$sql = "SELECT 
	tb1.payment_date,
	FORMAT(tb1.principal_payment,2) AS payment,
	FORMAT(tb1.interest,2) AS interest,
	IF(tb2.finance_month_profile_id = '', 'ชำระอื่น ๆ', 'ชำระรายเดือน') AS pay_desc,
	tb1.period_count
	FROM coop_finance_transaction AS tb1
	INNER JOIN coop_receipt AS tb2 ON tb1.receipt_id = tb2.receipt_id
	WHERE tb1.loan_id = {$loan_id} 
	ORDER BY tb1.payment_date DESC";
	$rs = $mysqli->query($sql);
	if ( $rs->num_rows ) {
		$data['transaction'] = 1;
		while( ($row = $rs->fetch_assoc()) ){
			$data['transactionData'][] = [ 
				'payment_date' => ( $row['payment_date'] == '' ) ? 'N/A' : dateDB2thaidate($row['payment_date'],true,false,false),
				'payment' => $row['payment'],
				'interest' => $row['interest'],
				'pay_desc' => $row['pay_desc'],
				'period_count' => $row['period_count']
			];
		}
	}
	 
	$sql = "SELECT 
	tb1.transaction_datetime,
	tb1.loan_atm_id, 
	FORMAT(tb1.loan_amount_balance, 2) AS loan_balance,
	IF(tb1.receipt_id, 'ชำระเงิน', 'ถอนเงิน ATM') AS trans_desc,
	IF(tb1.receipt_id, 1, 0) AS trans_type,
	FORMAT(tb2.loan_amount, 2) AS withdraw,
	FORMAT(tb3.interest, 2) AS interest,
	FORMAT(tb3.principal_payment, 2) AS principal_payment,
	IF(tb3.period_count, tb3.period_count, 'N/A') AS periods
	FROM coop_loan_atm_transaction AS tb1
	LEFT JOIN coop_loan_atm_detail AS tb2 ON tb1.transaction_datetime = tb2.loan_date AND tb1.loan_atm_id = tb2.loan_atm_id
	LEFT JOIN coop_finance_transaction AS tb3 ON tb1.receipt_id = tb3.receipt_id
	WHERE tb1.loan_atm_id = {$loan_id}
	ORDER BY tb1.transaction_datetime DESC";
	$rs = $mysqli->query($sql);
	if ( $rs->num_rows ) {
	    $data['transaction'] = 1;
	    while( ($row = $rs->fetch_assoc()) ){

	        $data['transactionData'][] = [ 
	            'payment_date' => ( $row['transaction_datetime'] == '' ) ? 'N/A' : dateDB2thaidate($row['transaction_datetime'],true,false),
	            'payment' => ( $row['trans_type'] == 1 ) ? $row['principal_payment'] : $row['withdraw'],
	            'interest' => ( $row['interest'] ) ? $row['interest'] : '0.00' ,
	            'pay_desc' => $row['trans_desc'],
	            'period_count' => $row['periods'],
	            'trans_type' => $row['trans_type']
	        ];
	    }
	}
	


    echo json_encode($data); 
    exit();
?>
