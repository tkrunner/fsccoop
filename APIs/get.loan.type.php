<?php
    require_once("config.inc.php");

    $data = [ 'status' => 0, 'responseText' => '' ];

    $sql = "SELECT * FROM coop_loan_name
            INNER JOIN coop_term_of_loan ON coop_loan_name.loan_name_id = coop_term_of_loan.type_id
            WHERE loan_type_id = 8 GROUP BY coop_loan_name.loan_name_id ORDER BY order_by ";
    $rs = $mysqli->query($sql);
    if ( $rs->num_rows ) {
        $data['status'] = 1;
        $data['responseText'] = "success";
        while($row = $rs->fetch_assoc()) {
            $data['data'][] = [
                'id' 	=> $row['loan_name_id'],
                'name' 	=> $row['loan_name'],
                'description' => $row["loan_name_description"]
            ];
        }
    }

    echo json_encode($data);
    exit();
?>