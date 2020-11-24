<?php
	require "../config.inc.php";
	// require PATH."/class/connect.inc.php";
	
	header("Content-type: application/json; charset=UTF-8");
	date_default_timezone_set('Asia/Bangkok');
	// require_once("token.validate.php");
	//  $member_no = $mysqli->real_escape_string(@$_REQUEST["mid"]);
	 $member_no = isset($_POST['mid']) ? $mysqli->real_escape_string($_POST['mid']) : null;
	 $bg_header = '';
	 $user_img = '';
	 if($member_no == 'demo_device'){
		 $member_no = '009999';
	 }

	 $sql = "SELECT firstname_th,lastname_th,sex,member_pic FROM coop_mem_apply WHERE member_id = '{$member_no}'";
	 $rs = $mysqli->query($sql);
	 if ( $rs->num_rows ) {
		 $row = $rs->fetch_assoc();
		//  $user_img = '';
		 if ( $row['member_pic'] == '' ) {
			 $user_img = ( $row['sex'] == 'F' ) ? 'https://api.coop.in.th/mla/img/female.png' : 'https://api.coop.in.th/mla/img/male.png';
		 } else {
			//  $user_img = SYSTEM_URL.'assets/uploads/members/'.$row['member_pic'];
			$user_img = '';
		 }
		 $data['member_name'] = 'คุณ'.$row['firstname_th'];
		 $data['namefull'] = $row['firstname_th'].' '.$row['lastname_th'];
		 $data['user_img'] = $user_img;
	 }

	//  $sql = "SELECT * FROM cmp_background_app WHERE bkapp_id = 1";
	//  $rs = $mysqli->query($sql);
	//  $row = $rs->fetch_assoc();
	//  $bg_header= $row['bkapp_picture'];
	//  if ( $rs->num_rows ) {
	// 	 $data['is_icon'] = 1;
	// 	 $data['count'] = $rs->num_rows;
	// 	 while( ($row = $rs->fetch_assoc()) ){
	// 		 $data['icon'][] = [
	// 			 'icon_img' => 'ws_mobile/'.trim($row['icon_img']),
	// 			 'icon_name' => trim($row['icon_name']),
	// 			 'icon_name_sub' => htmlspecialchars_decode($row['icon_name'] , ENT_QUOTES ),
	// 			 'icon_shortname' => trim($row['icon_shortname'])
	// 		 ];
	// 	 }
	//  }

	 $data = [
		'background_img' => 'https://system.bbcoop.or.th/APIs/img/bg_mobile.png',
		'user_img' =>  $user_img,
		'member_name' =>  $data['member_name'],
		// 'user_img' => 'https://apps2.coop.ku.ac.th/asset/member_photo/'.$member_no.'.png',
		// 'deposit_sum' => number_format($rowdeposit["PRNCBAL"], 2),
	];


	 // ICON
	 $sql = "SELECT * FROM mobile_icon WHERE icon_active = 1 ORDER BY icon_sort ASC";
	 $rs = $mysqli->query($sql);
	 if ( $rs->num_rows ) {
		 $data['is_icon'] = 1;
		 $data['count'] = $rs->num_rows;
		 while( ($row = $rs->fetch_assoc()) ){
			 $data['icon'][] = [
				 'icon_img' => $row['icon_img'],
				 'icon_name' => $row['icon_name'],
				 'icon_name_sub' => htmlspecialchars_decode($row['icon_name'] , ENT_QUOTES ),
				 'icon_shortname' => $row['icon_shortname']
			 ];
		 }
	 }
	 // ICON

	 //Icon sub
	 $sql = "SELECT * FROM mobile_icon WHERE icon_active = 2 ORDER BY icon_id ASC";
	 $rs = $mysqli->query($sql);
	 if ( $rs->num_rows ) {
		 $data['is_icon'] = 1;
		 $data['count'] = $rs->num_rows;
		 while( ($row = $rs->fetch_assoc()) ){
			 $data['iconsub'][] = [
				 'icon_img' => 'ws_mobile/'.trim($row['icon_img']),
				 'icon_name' => trim($row['icon_name']),
				 'icon_shortname' => trim($row['icon_shortname'])
			 ];
		 }
	 }

	 $badgeSQL = "SELECT  tb1.msg_id AS badge ,tb1.msg_id, tb1.msg_title, tb1.create_date,tb3.user_read
        FROM mobile_message AS tb1
            INNER JOIN mobile_message AS tb2 ON tb1.msg_id = tb2.msg_id
            INNER JOIN mobile_message_validate AS tb3 ON tb1.msg_id = tb3.msg_id
             WHERE tb3.member_id = '{$member_no}' AND tb3.user_delete = 0 AND tb3.user_read = 0
            GROUP BY msg_id
             ORDER BY tb1.create_date DESC";
        
        $badgeRS = $mysqli->query($badgeSQL);
        $data['badge'] = $badgeRS->num_rows;
	 //icon sub
	//  //NEWS
	 $sql = "SELECT * FROM cmp_news LEFT JOIN admin ON cmp_news.admin_id = admin.admin_id 
	 WHERE cmp_news.news_status = 1 AND  cmp_news.publicdate < NOW()  ORDER BY cmp_news.publicdate DESC LIMIT 0 , 2";
	 $rs = $mysqli_app->query($sql);
	 if ( $rs->num_rows ) {
		 $data['is_icon'] = 1;
		 $data['count'] = $rs->num_rows;
		 while( ($row = $rs->fetch_assoc()) ){
			 $data['news'][] = [
				 'news_pic' => !empty($row['news_picture']) ? 'uploads/contents/'.trim($row['news_picture'])  : 'uploads/contents/default_new.png',
				 'news_id' => $row['news_id'],
				 'news_title' => $row['news_title'],
				 'news_detail' => $row['news_detail'],
				 'news_url' => $row['news_url'],
			 ];
		 }
	 }
	 //NEWS

	//MESSAGE
	// $member_id = isset($_POST['member_no']) ? $mysqli->real_escape_string($_POST['member_no']) : null;
    // $sql = "SELECT tb1.msg_id, tb1.msg_title, tb1.msg_url, tb1.msg_senddate,tb2.user_delete
    // , CASE WHEN tb2.msg_id IS NULL THEN 0 ELSE 1 END AS is_fetch
    // , CASE WHEN tb2.is_read IS NULL THEN 0 ELSE tb2.is_read END AS is_read
    // , CASE WHEN tb2.user_delete IS NULL THEN 0 ELSE tb2.user_delete END AS user_delete
    // FROM cmp_message AS tb1
    // LEFT OUTER JOIN (
    //     SELECT msg_id, is_read,user_delete
    //         FROM cmp_imp_member_message
    //         WHERE member_no = '{$member_id}'
    // ) AS tb2 ON tb1.msg_id = tb2.msg_id
    // WHERE tb1.msg_status = 1
    // AND tb1.msg_senddate < NOW()
    // AND tb2.user_delete IS NULL OR tb2.user_delete = 0
    // ORDER BY tb1.msg_id DESC";
    //     $rs = $mysqli->query($sql);
        
    // if ( $rs->num_rows ) {
    //     while( ($row = $rs->fetch_assoc()) ){
    //         $data['data'][] = [
    //             'msg_id' => $row['msg_id'],
    //             'msg_title' => $row['msg_title'],
    //             'is_read' => $row['is_read'],
    //         ];
    //     }

	// $badgeSQL = "SELECT tb1.msg_id AS badge FROM mobile_message_validate AS tb1
    //     INNER JOIN mobile_message AS tb2 ON tb1.msg_id = tb2.msg_id
    //     WHERE trim(tb1.member_no) = trim('{$member_no}') AND tb1.user_delete = 0 AND tb2.admin_delete = 0 AND tb1.user_read = 0 GROUP BY tb2.msg_id";
	// 	$badgeRS = $mysqli->query($badgeSQL);
	// 	$row = $badgeRS->fetch_assoc();
	// 	$data['badge'] = $badgeRS->num_rows;
	// 	$data['sql_badge'] = $badgeSQL;
        

        
// }

	// echo '<pre>',print_r($data,1),'</pre>';
	echo json_encode($data) ; 

 
	exit();