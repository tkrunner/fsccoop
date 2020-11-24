<?php
require "../config.inc.php";
// require PATH."/class/connect.inc.php";

header("Content-type: application/json; charset=UTF-8");
date_default_timezone_set('Asia/Bangkok');
	
	$member_no = $mysqli->real_escape_string(@$_REQUEST["mid"]);
	$count = $mysqli->real_escape_string(@$_REQUEST["count"]);
	
	if(!$count){
		$count = 6;
	}

    //NEWS
	 $sql = " SELECT * FROM cmp_news LEFT JOIN admin ON cmp_news.admin_id = admin.admin_id 
	 WHERE cmp_news.news_status = 1 AND  cmp_news.publicdate < NOW()  ORDER BY cmp_news.publicdate DESC LIMIT 0 , {$count}";
	 $rs = $mysqli_app->query($sql);
	 if ( $rs->num_rows ) {
		 $data['is_icon'] = 1;
		 $data['count'] = $rs->num_rows;
		 while( ($row = $rs->fetch_assoc()) ){
			 $data['news'][] = [
				 'news_pic' => !empty($row['news_picture']) ? 'uploads/contents/'.$row['news_picture']  : 'uploads/contents/default_new.png',
				 'news_id' => $row['news_id'],
				 'news_title' => $row['news_title'],
				 'news_detail' => $row['news_detail'],
				 'news_url' => $row['news_url'],
			 ];
		 }
	 }
	 //NEWS

	
	
	echo json_encode($data) ; 
 
	exit();
?>