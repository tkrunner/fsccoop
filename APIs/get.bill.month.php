<?php
    require_once("config.inc.php");
    require_once("token.validate.php");
    $data = [ 'status' => 0, 'responseText' => '' ];

    $year = isset($_POST['year']) ? $mysqli->real_escape_string($_POST['year']) : null;
    $year = $year === null ? date("Y") : ((int)$year - 543);
    // $year = $year === null ? date("Y") : ((int)$year);

    $sql = "SELECT 
    MONTH(tb2.receipt_datetime) AS months
    FROM coop_finance_transaction AS tb1
    INNER JOIN coop_receipt AS tb2 ON tb1.receipt_id = tb2.receipt_id
    WHERE tb1.member_id = '{$member_id}' AND YEAR(tb2.receipt_datetime) = '{$year}'
    GROUP BY MONTH(tb2.receipt_datetime)
    ORDER BY tb2.receipt_datetime DESC";
    $rs = $mysqli->query($sql);
    if ( $rs->num_rows ) {
        $data['status'] = 1;
        while( ($row = $rs->fetch_assoc()) ){
            $data['data'][] = [
                'month_id' => $row['months'],
                'month' => getMonths($row['months'])
            ];
        }
    }

    echo json_encode($data); 
    exit();
?>
