<?php
require "../config.inc.php";
	// require PATH."/class/connect.inc.php";
	
	header("Content-type: application/json; charset=UTF-8");
	date_default_timezone_set('Asia/Bangkok');

    //condition
	 $sql = "SELECT * FROM condition_register ORDER BY update_date DESC LIMIT 0 , 1";
	 $rs = $mysqli_app->query($sql);
	 if ( $rs->num_rows ) {
		 $data['is_condition'] = 1;
		//  $data['count'] = $rs->num_rows;
		 while( ($row = $rs->fetch_assoc()) ){
			 $detail_text = htmlentities($row["condition_detail"]);
			 $data['condition'][] = [
				 'condition_title' => htmlspecialchars_decode(trim($row['condition_title'])),
				 'condition_detail' =>htmlspecialchars_decode($row["condition_detail"]),
				 'update_date' => trim($row['update_date']),
			 ];
		 }
	 }
	 //condition

	
	
	echo json_encode($data) ; 
 
	exit();
?>