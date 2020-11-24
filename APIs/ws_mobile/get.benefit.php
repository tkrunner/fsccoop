<?php
    require_once("../config.inc.php");
    // require_once("config.inc.php");
    require_once("../token.validate.php");
    $data = [ 'status' => 0, 'responseText' => '' ];
    $sum = 0;
    // $member_id = $_GET['member_id'];

    $sql = "SELECT * FROM coop_benefits_type_detail WHERE start_date < NOW() ORDER BY benefits_id, start_date DESC";
    $rs = $mysqli->query($sql);

    if ( $rs->num_rows ) {

        $benefits = array();
		$prev_benefit_ids = array();
        while( ($row = $rs->fetch_assoc()) ){
            $sum = $sum+$row['payment_receive'];
            if (!in_array($row["benefits_id"], $prev_benefit_ids)) {
                $benefits_id = $row['benefits_id'];
                $detail = "SELECT benefits_name FROM coop_benefits_type WHERE benefits_id = {$benefits_id}";
                $rsDetail = $mysqli->query($detail);

                if ( $rsDetail->num_rows ) {
                    $data['status'] = 1;
                    $rowDetail = $rsDetail->fetch_assoc();
                    $row['member_id'] = $member_id;
                    $data['benefit'][] = [
                        'benefit_name'  =>  $rowDetail['benefits_name'],
                        'available' =>   number_format($row['payment_receive'], 2),
                        'date' =>  dateDB2thaidate($row['updatetime'],true,false,false),
                    ];
                    $data['benefit_sum'] = number_format($sum, 2);
                    // echo $validate->benefit_validate($row).' '.$rowDetail['benefits_name']."\n";
                }
                
                $prev_benefit_ids[] = $benefits_id;
            }

        }
        
    }

    echo json_encode($data); 
    exit();

?>