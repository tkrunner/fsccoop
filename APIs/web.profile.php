<?php
	
    require_once("config.inc.php");
    require_once("token.validate.php");

    $data = [ 'status' => 1, 'responseText' => '', 'is_icon' => 0, 'background_img' => ONLINE_URL.'APIs/img/background_img.png' ];
 
  	
    $sql = "SELECT firstname_th,lastname_th,sex,member_pic FROM coop_mem_apply WHERE member_id = '{$member_id}'";
    $rs = $mysqli->query($sql);
    if ( $rs->num_rows ) {
        $row = $rs->fetch_assoc();
        $user_img = '';
        if ( $row['member_pic'] == '' ) {
            // $user_img = ( $row['sex'] == 'F' ) ? ONLINE_URL.'APIs/img/female.png' : ONLINE_URL.'APIs/img/male.png';
			$user_img = ( $row['sex'] == 'F' ) ? 'https://dev.policehospital-coop.com/assets/uploads/members/default.png' : 'https://dev.policehospital-coop.com/assets/uploads/members/default.png'  ;
        } else {
            $user_img = SYSTEM_URL.'assets/uploads/members/'.$row['member_pic'];
        }
        $data['member_name'] = 'คุณ'.$row['firstname_th'].' '.$row['lastname_th'];
        $data['user_img'] = $user_img;
		
		$sql = "SELECT login_date FROM login_session WHERE token = '{$token}' AND platform = '{$platform}' AND is_use = 1";
		$rs = $mysqli->query($sql);
		$lastlogin = $rs->fetch_assoc(); 
		$data['lastlogin'] = dateDB2thaidate($lastlogin["login_date"], true, true , false)  ;
    }

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
                'icon_shortname' => $row['icon_shortname']
            ];
        }
    }
    // ICON

    echo json_encode($data); 
    exit();
?>