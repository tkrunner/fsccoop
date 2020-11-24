<?php
    require_once("config.inc.php");
    require_once("token.validate.php");

    $data = [ 'status' => 0, 'responseText' => '' ];


    $sql = "SELECT 
    YEAR(tb2.receipt_datetime) + 543 AS years
    FROM coop_finance_transaction AS tb1
    INNER JOIN coop_receipt AS tb2 ON tb1.receipt_id = tb2.receipt_id
    WHERE tb1.member_id = '{$member_id}'
    GROUP BY YEAR(tb2.receipt_datetime)
    ORDER BY YEAR(tb2.receipt_datetime) DESC";
    $rs = $mysqli->query($sql);
    if ( $rs->num_rows ) {
        $data['status'] = 1;
        while( ($row = $rs->fetch_assoc()) ){
            $data['data'][] = [
                'year' => $row['years']
            ];
        }
    }

    echo json_encode($data); 
    exit();
?>
