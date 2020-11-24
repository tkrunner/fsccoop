<?php
    require_once("config.inc.php");
    require_once("parameter.inc.php");
    require_once("token.validate.php");

    $validate = new Validate($mysqli);

    $data = [ 'status' => 0, 'responseText' => '' ];

    $sql = "SELECT * FROM coop_benefits_type_detail WHERE start_date < NOW() ORDER BY benefits_id, start_date DESC";
    $rs = $mysqli->query($sql);
    if ( $rs->num_rows ) {

        $benefits = array();
		$prev_benefit_ids = array();
        while( ($row = $rs->fetch_assoc()) ){
            
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
                        'available' =>  $validate->benefit_validate($row)
                    ];
                    // echo $validate->benefit_validate($row).' '.$rowDetail['benefits_name']."\n";
                }
                
                $prev_benefit_ids[] = $benefits_id;
            }

        }
        
    }

    echo json_encode($data); 
    exit();
?>