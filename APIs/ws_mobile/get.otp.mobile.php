<?php
    header("Content-type: application/json; charset=UTF-8");
    date_default_timezone_set('Asia/Bangkok');
    require_once("../config.inc.php");
    // require PATH."/class/function.inc.php";

    
    $member = isset($_POST['member']) ? $mysqli->real_escape_string($_POST['member']) : null;
    $idCard = isset($_POST['idCard']) ? $mysqli->real_escape_string($_POST['idCard']) : null;
    $mobile = isset($_POST['mobile']) ? $mysqli->real_escape_string($_POST['mobile']) : null;
    $mobile_uid = isset($_POST['mobile_uid']) ? $mysqli->real_escape_string($_POST['mobile_uid']) : null;
    $platform = isset($_POST['platform'])? $mysqli->real_escape_string($_POST['platform']) : null;
    $hash = isset($_POST['Hash'])? $mysqli->real_escape_string($_POST['Hash']) : null;
    
    $data = array();

    if(strtolower($platform) == 'ios' AND $version == '1.3.0'){
        $u_id = 'demo_device';
}



$number_otp = rand(100000, 999999);
$time_start = date("Y-m-d H:i:s");
$time_end = date("Y-m-d H:i:s", strtotime("+5 minutes"));
$date_end_msg = date("d-m-Y-H:i:s",strtotime($time_end)).'น.';
$msg = "รหัสOTPสำหรับการดำเนินการคือ {$number_otp} ใช้ได้ถึง {$date_end_msg} {$hash}";
$mobile = str_replace("-", "", $mobile);
$status_sms = send_sms($mobile, $msg);

//SEND SMS
$number_otp = (string)$number_otp;
$token_msg = get_token($member);
    // //SEND SMS
    $number_otp = (string)$number_otp;
    $token_msg = get_token($member);
        
        $updateOTP = "UPDATE mobile_token SET  OTP ='{$number_otp}' , OTP_Start='{$time_start}', OTP_End='{$time_end}', create_date =NOW() WHERE member_id = '{$member}'  ";

        if($mysqli->query($updateOTP) === TRUE){
            $data['member_no'] = $member;
            $data['token'] = $token_msg;
            $data['OTP'] = $number_otp;
            $data['Hash'] = $hash;
            // $data['mobile_id'] = $mysqli->insert_id;
        }else{
            $data['responseText'] = 'เกิดข้อผิดพลาดบางอย่าง  กรุณาลองอีกครั้ง หรือสอบถามเจ้าหน้าที่สหกรณ์  ที่เบอร์'."<br>".' 053-851888';
            echo json_encode($data);
            exit();
        }

    echo json_encode($data); 
    exit();
?>
