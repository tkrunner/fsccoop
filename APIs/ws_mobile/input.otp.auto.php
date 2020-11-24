<?php 
    require_once("../config.inc.php");

    $data = [ 'status' => 0, 'responseText' => 'เกิดความผิดพลาดบางประการ' ];

    $member_no = isset($_POST['member_no']) ? $mysqli->real_escape_string($_POST['member_no']) : null;
    // $OTP = isset($_POST['OTP']) ? $mysqli->real_escape_string($_POST['OTP']) : null;
    // $via = isset($_POST['via']) ? $mysqli->real_escape_string($_POST['via']) : null;
    // //mobile
    // $dataString = isset($_POST['data']) ? $mysqli->real_escape_string($_POST['data']) : null;
    // $dataObject = json_decode(str_replace("\\", "", $dataString));

 
        $sql = "SELECT OTP FROM mobile_token WHERE member_id = '{$member_no}' AND is_delete = 0 ORDER BY create_date DESC LIMIT 1";
    $rs = $mysqli->query($sql);
    if ( $rs->num_rows ) {
        $row = $rs->fetch_assoc();
         $data['status'] = 1;
         $data['OTP'] = $row['OTP'];
    } else {
        $data['responseText'] =  'ไม่พบข้อมูลรหัส OTP นี้ในระบบ';
        $data['sql'] = $sql;

    }

    echo json_encode($data); 
    exit();
?>