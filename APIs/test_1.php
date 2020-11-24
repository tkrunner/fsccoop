<?php 
ini_set('display_errors', 1);
	require_once("config.inc.php");
    require_once("parameter.inc.php");

	$sql = "SELECT * FROM coop_mem_apply";
	$rs = $mysqli->query($sql);
	// echo("Error description: " . );

    // if ( $rs->num_rows ) {
	// 	$data['responseError'] = 'พบภาระการค้ำประกัน';
        while( ($row = $rs->fetch_assoc()) ){
			$data['firstname_th']    =  $row['firstname_th'];
            $data['lastname_th'] = $row['lastname_th'];
        }
    // } else {
    //     $data['responseError'] = 'ไม่พบภาระการค้ำประกัน';
    // }

	echo json_encode($data);
    exit();

?>
