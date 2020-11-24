<?php 
    require_once("config.inc.php");
    require_once("token.validate.php");

    $data = [ 'status' => 0, 'responseText' => 'เกิดความผิดพลาดบางประการ กรุณาลองใหม่อีกครั้ง' ];

    $password = isset($_POST['password']) ? $mysqli->real_escape_string($_POST['password']) : null;
	$password2 = isset($_POST['password2']) ? $mysqli->real_escape_string($_POST['password2']) : null;
	$passwordold = isset($_POST['passwordold']) ? $mysqli->real_escape_string($_POST['passwordold']) : null;
	 
		
    $via = isset($_POST['via']) ? $mysqli->real_escape_string($_POST['via']) : null;

    $encry_password = encrypt_text($password);

    if ( $via == 'website' ) {
		 
		$sql = "SELECT password, is_active FROM web_online_account WHERE member_id = '{$member_id}' ORDER BY create_date DESC";
		$rs = $mysqli->query($sql);
		echo $mysqli->error ; 
		$row = $rs->fetch_assoc();
		
		if ($password == decrypt_text($row['password'])) {
			$data["status"] = 0 ; 
			$data["responseText"] = "รหัสผ่านเดิมไม่ถูกต้อง " ; 
			echo json_encode($data);
			exit();
		}
		
        if(!strlen($password) == 6){
			$data["status"] = 0 ; 
			$data["responseText"] = "รหัสผ่าน 0-9 จำนวน 6 หลัก " ; 
			echo json_encode($data);
			exit();
		}
		
		$dump = preg_replace('/[0-9]+/', '', $password1);
		if(!empty($dump)){
			$data["status"] = 0 ; 
			$data["responseText"] = "รหัสผ่าน 0-9 จำนวน 6 หลัก" ; 
			echo json_encode($data);
			exit();
		}
		
		if($password !== $password2  ){
			$data["status"] = 0 ; 
			$data["responseText"] = "รหัสผ่านไม่ตรงกัน" ; 
			echo json_encode($data);
			exit();
		}
		
		
		$sql = "UPDATE web_online_account SET password = '{$encry_password}', is_active = 1 WHERE member_id = '{$member_id}'";

    }

    if ( $mysqli->query($sql) === TRUE ) {
        $data['status'] = 1;
        $data['responseText'] = '';
    }

    echo json_encode($data); 
    exit();
?>