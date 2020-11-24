<?php
 require_once("config.inc.php");
// require_once("parameter.inc.php");

$sql = "SELECT
m.YEAR
FROM
coop_dividend_average_master m";
    $rs = $mysqli->query($sql);
    if ( $rs->num_rows ) {
        while( ($row = $rs->fetch_assoc()) ){
            $data['diviendYear'][] = [
                // 'YEAR'  =>  (string)($row['YEAR'] + 543),
                'YEAR'  =>  (string)($row['YEAR'] + 543),
                'yearText'  =>  'ปี '.(string)($row['YEAR'] + 543)
            ];
        }
    } else {
        $data['diviendYear'] = 'ไม่พบผู้รับผลประโยชน์';
    }

    echo json_encode($data);
    exit();   

?>
