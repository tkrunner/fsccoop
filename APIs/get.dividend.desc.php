<?php
    require_once("config.inc.php");

    $data = [ 'status' => 0, 'responseText' => '' ];

    require_once("parameter.inc.php");
    require_once("token.validate.php");

    $year = isset($_POST['year']) ? $mysqli->real_escape_string($_POST['year']) : null;

    $year = (int)$year - 543;

    $total_average = 0;
    $sql = "SELECT tb1.dividend_value, tb1.average_return_value, tb1.master_id, tb2.status,
    CASE tb2.status
        WHEN '0' THEN 'รออนุมัติ'
        WHEN '1' THEN 'อนุมัติ'
        WHEN '2' THEN 'ไม่อนุมัติ'
    END dividend_status
    FROM coop_dividend_average AS tb1
    INNER JOIN coop_dividend_average_master AS tb2 ON tb2.id = tb1.master_id
    WHERE tb1.member_id = '{$member_id}' AND tb1.year = {$year} 
    ORDER BY tb1.date_create DESC LIMIT 1";
    $rs = $mysqli->query($sql);
    if ( $rs->num_rows ) {
        $data['status'] = 1;
        $row = $rs->fetch_assoc();

        $master_id = (int)$row['master_id'];

        $total_average = $row['dividend_value'] + $row['average_return_value'];

        $data['dividend'] = [
            'dividend_value' => number_format($row['dividend_value'], 2),
            'average_return_value' => number_format($row['average_return_value'], 2),
            'status' => $row['dividend_status'],
            'status_code' => $row['status']
        ];

        $deduct_total = 0;
        $sqlDeduct = "SELECT tb1.amount, tb2.deduct_name 
        FROM coop_dividend_deduct AS tb1
        INNER JOIN coop_dividend_deduct_type AS tb2 ON tb2.deduct_id = tb1.deduct_id
        WHERE tb2.deduct_status = 1 AND tb1.member_id = '{$member_id}' AND tb1.master_id = {$master_id}";
        $rsDeduct = $mysqli->query($sqlDeduct);
        $data['deduct_test'] = $rsDeduct->num_rows;
        if ( $rsDeduct->num_rows ) {

            while( ($rowDeduct = $rsDeduct->fetch_assoc()) ){
                $deduct_total = $deduct_total + $rowDeduct['amount'];
                $data['deduct'][] = [
                    'deduct_name'   =>  $rowDeduct['deduct_name'],
                    'amount'    =>  number_format($rowDeduct['amount'], 2)
                ];
            }

        } else {
            $data['deduct'] = [];
        }
    }

    $data['total'] = number_format($total_average - $deduct_total, 2);

    echo json_encode($data); 
    exit();
?>