<?php
require "../config.inc.php";
// require PATH."/class/connect.inc.php";

header("Content-type: application/json; charset=UTF-8");
date_default_timezone_set('Asia/Bangkok');
// require_once("token.validate.php");
//  $member_no = $mysqli->real_escape_string(@$_REQUEST["mid"]);
 	$member_no = isset($_POST['mid']) ? $mysqli->real_escape_string($_POST['mid']) : null;
	$data['news_id'] = $member_no;
	// if(!$count){
	// 	$count = 6;
	// }

    //NEWS
	//  $sql = "SELECT * ,SUBSTRING(cmp_news.publicdate, 1, 10) AS public_date,SUBSTRING(cmp_news.publicdate, 12, 20) 
	//  AS public_time,REPLACE(cmp_news.news_detail, 'http:', 'https:') AS reurl FROM cmp_news LEFT JOIN admin ON cmp_news.admin_id = admin.admin_id 
	// 	  WHERE cmp_news.news_status = 1 AND  news_id = '{$member_no}'
	// 	  ORDER BY cmp_news.publicdate DESC";
	
	// $rs = $mysqli_app->query($sql);
	//  if ( $rs->num_rows ) {
	// 	 $data['is_icon'] = 1;
	// 	 while( ($row = $rs->fetch_assoc()) ){
	// 		 $data['news'][] = [
	// 			 'news_pic' => 'uploads/contents/'.trim($row['news_picture']),
	// 			 'news_id' => trim($row['news_id']),
	// 			 'news_title' => trim($row['news_title']),
	// 			 'news_detail' => htmlspecialchars_decode($row["reurl"] , ENT_QUOTES ),
	// 			 'publicdate' => ConvertToThaiDate($row["public_date"], true),
	// 			 'publictime' => $row["public_time"],
	// 			//  'publicdate' => ConvertToThaiDate($row["public_date"], true),
	// 		 ];
	// 	 }
	//  }
	//  $data['sql'] = $sql;
	$sql = "SELECT * ,SUBSTRING(cmp_news.publicdate, 1, 10) AS public_date,SUBSTRING(cmp_news.publicdate, 12, 20) 
	  AS public_time,REPLACE(cmp_news.news_detail, 'http:', 'https:') AS reurl FROM cmp_news LEFT JOIN admin ON cmp_news.admin_id = admin.admin_id 
	 	  WHERE cmp_news.news_status = 1 AND  news_id = '{$member_no}'
	 	  ORDER BY cmp_news.publicdate DESC";
	 $rs = $mysqli_app->query($sql);
	 if ( $rs->num_rows ) {
		 $data['is_icon'] = 1;
		 $data['count'] = $rs->num_rows;
		 while( ($row = $rs->fetch_assoc()) ){
			 $data['news'][] = [
				'news_pic' => 'uploads/contents/'.$row['news_picture'],
							 'news_id' => $row['news_id'],
							 'news_title' => $row['news_title'],
							 'news_detail' => htmlspecialchars_decode($row["reurl"] , ENT_QUOTES ),
							 'publicdate' => dateDB2thaidate($row['public_date'],true,false),
							 'publictime' => $row["public_time"],
			 ];
		 }
	 }
	 //NEWS

	
	
	echo json_encode($data) ; 
 
	exit();
?>