<?php
    require_once("config.inc.php");

    // $data = [ 'status' => 0, 'responseText' => '' ];

    require_once("parameter.inc.php");
    require_once("token.validate.php");

    // $data['member_id'] = $member_id;
    $member_id = isset($_POST['member_no']) ? $mysqli->real_escape_string($_POST['member_no']) : null;
    $sql = "SELECT 
    tb1.loan_id,
    IF(tb1.guarantee_person_amount = null, '0.00', FORMAT(tb1.guarantee_person_amount, 2)) AS guarantee_amount,
    IF(tb2.loan_status = '1','ปกติ','เบี้ยวหนี้') AS loan_status,
    IF(tb2.contract_number = '', 'N/A', tb2.contract_number) AS contract_number,
    IF(tb2.loan_amount = null, '0.00', FORMAT(tb2.loan_amount, 2)) AS loan_amount,
    IF(tb2.loan_amount_balance = null, '0.00', FORMAT(tb2.loan_amount_balance, 2)) AS loan_amount_balance,
    tb3.firstname_th AS firstname_loan_person,
    tb3.lastname_th AS lastname_loan_person,
    tb4.prename_short
    FROM coop_loan_guarantee_person AS tb1
    INNER JOIN coop_loan AS tb2 ON tb1.loan_id = tb2.id
    INNER JOIN coop_mem_apply AS tb3 ON tb2.member_id = tb3.member_id
    INNER JOIN coop_prename AS tb4 ON tb3.prename_id = tb4.prename_id
    WHERE tb1.guarantee_person_id = '{$member_id}' AND loan_status = '1'
    OR tb1.guarantee_person_id = '{$member_id}' AND loan_status = '6'
    ORDER BY tb2.createdatetime DESC";
    $rs = $mysqli->query($sql);
    if ( $rs->num_rows ) {
        $data['status'] = 1;
        while( ($row = $rs->fetch_assoc()) ){
            $data['data'][] = [
                'loan_id' => $row['loan_id'],
                'guarantee_amount' => $row['guarantee_amount'],
                'loan_status' => $row['loan_status'],
                'contract_number' => $row['contract_number'],
                'loan_amount' => $row['loan_amount'],
                'loan_amount_balance' => $row['loan_amount_balance'],
                'loan_name' => $row['prename_short']." ".$row['firstname_loan_person']." ".$row['lastname_loan_person']
            ];
        }
    }

    echo json_encode($data);
    exit();
?>