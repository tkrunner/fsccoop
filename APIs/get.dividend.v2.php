<?php
    require_once("config.inc.php");

    $data = [ 'status' => 0, 'responseText' => '' ];

    require_once("parameter.inc.php");
    require_once("token.validate.php");

    $sql = "SELECT year FROM coop_dividend_average WHERE member_id = '{$member_id}' ORDER BY year DESC";
    $rs = $mysqli->query($sql);
    if ( $rs->num_rows ) {
        $data['status'] = 1;
        while(( $row = $rs->fetch_assoc() )) {
            $data['diviend'][] = [
                'year'  =>  (string)($row['year'] + 543),
                'yearText'  =>  'ปี '.(string)($row['year'] + 543)
            ];
        }
    } 

    echo json_encode($data);
    exit();
?>