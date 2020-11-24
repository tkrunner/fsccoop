<?php
    require_once("../config.inc.php");
    // require_once("../token.validate.php");

    $data = [ 'status' => 0, 'responseText' => '' ];
    $member_id = '999999';
    $contract_number = 'ฉอ.6300125';
    $data['data'] = [];
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
    tb1.createdatetime,
    IF(tb1.pay_type = '1', 'N/A', (SELECT FORMAT(total_paid_per_month, 2) FROM coop_loan_period WHERE loan_id = tb1.id LIMIT 1)) AS pay_per_month,
    tb3.loan_type
    FROM coop_loan AS tb1
    INNER JOIN coop_loan_name AS tb2 ON tb1.loan_type = tb2.loan_name_id
    INNER JOIN coop_loan_type AS tb3 ON tb2.loan_type_id = tb3.id
    WHERE tb1.member_id = '{$member_id}' AND loan_status = '1' AND tb1.contract_number = '{$contract_number}'
    ORDER BY createdatetime DESC";
    $rs = $mysqli->query($sql);
    if ( $rs->num_rows ) {
        $data['status'] = 1;
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
                'pay_per_month' => ( $row['pay_per_month'] ) ? $row['pay_per_month'] : 'N/A',
                'loan_type' => $row['loan_type'],
                'loan' => 'loan',
                'create_date' => $row['createdatetime']
            ];
        }
    }
    $sql = "SELECT FORMAT(total_amount, 2) as  total_amount ,approve_date,contract_number,FORMAT(total_amount_balance, 2) as total_amount_balance ,max_period,loan_atm_status
    ,createdatetime FROM coop_loan_atm AS tb1
    WHERE member_id = '{$member_id}' AND loan_atm_status = '1' AND contract_number = '{$contract_number}'
    ORDER BY createdatetime DESC";
    $rs = $mysqli->query($sql);
    if ( $rs->num_rows ) {
        $data['status'] = 1;
        while( ($row = $rs->fetch_assoc()) ){
            $data['data']= [
                'approve_date' => ($row['approve_date'] == null) ? 'N/A' : dateDB2thaidate($row['approve_date'],true,false,false),
                'contract_number' => $row['contract_number'],
                'loan_amount' => $row['total_amount'],
                'loan_amount_balance' => $row['total_amount_balance'],
                'max_period' => ($row['max_period'] == null OR $row['max_period'] == 0) ? 'N/A' : $row['max_period'],
                'loan_status' => $row['loan_atm_status'],
                'loan_status_desc' => ($row['loan_atm_status'] == 1 ? 'อนุมัติ':'-'),
                'pay_type' => $row['pay_type'],
                'pay_per_month' => ( $row['pay_per_month'] ) ? $row['pay_per_month'] : 'N/A',
                'loan_type' => 'เงินกู้ฉุกเฉิน ATM',
                'loan' => 'loanATM',
                'create_date' => $row['createdatetime']
            ];
        }
    }

    print_r($data);

    // echo json_encode($data);
    exit();
?>
