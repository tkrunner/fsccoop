<?php

    require_once("config.inc.php");

    $data = [ 'status' => 0, 'responseText' => '' ];

    require_once("parameter.inc.php");
    require_once("token.validate.php");

    $year = (date("Y") + 543);

    $sql = "SELECT
        t1.insurance_id,
        t1.member_id,
        t1.insurance_year,
        t1.insurance_date,
        t1.loan_id,
        t1.contract_number,
        t1.insurance_amount,
        t1.insurance_premium,
        t2.insurance_type_name
    FROM
        coop_life_insurance AS t1
    LEFT JOIN coop_life_insurance_type AS t2 ON t1.insurance_type = t2.insurance_type_id
    WHERE t1.member_id = '{$member_id}' AND t1.insurance_status = '1' AND t1.insurance_year = '{$year}'
    ORDER BY t1.insurance_year DESC";
    $rs = $mysqli->query($sql);
    if ( $rs->num_rows ) {
        $data['status'] = 1;
        while( ($row = $rs->fetch_assoc()) ){
            $data['data'][] = [
                'insurance_id'  =>  $row['insurance_id'],
                'member_id' =>  $row['member_id'],
                'insurance_year'    =>  $row['insurance_year'],
                'insurance_date'    =>  dateDB2thaidate($row['insurance_date']),
                'loan_id'   =>  $row['loan_id'],
                'contract_number'   =>  $row['contract_number'],
                'insurance_amount'  =>  number_format($row['insurance_amount'], 2),
                'insurance_premium' =>  number_format($row['insurance_premium'], 2),
                'insurance_type_name'   =>  $row['insurance_type_name']
            ];
        }
    }

    echo json_encode($data); 
    exit();

?>