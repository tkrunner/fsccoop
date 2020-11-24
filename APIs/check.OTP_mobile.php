<?php 
    require_once("config.inc.php");

    $data = [ 'status' => 0, 'responseText' => 'เกิดความผิดพลาดบางประการ' ];

    $account_id = isset($_POST['account_id']) ? $mysqli->real_escape_string($_POST['account_id']) : null;
    $OTP = isset($_POST['OTP']) ? $mysqli->real_escape_string($_POST['OTP']) : null;
    $via = isset($_POST['via']) ? $mysqli->real_escape_string($_POST['via']) : null;
    //mobile
    $dataString = isset($_POST['data']) ? $mysqli->real_escape_string($_POST['data']) : null;
    $dataObject = json_decode(str_replace("\\", "", $dataString));

    if ( $via == 'website' ) {
        $sql = "SELECT web_id, OTP, OTP_End FROM web_online_account WHERE web_id = {$account_id}";
    } else {
        $sql = "SELECT OTP,OTP_Start,OTP_End FROM mobile_token 
    WHERE member_id = '{$dataObject->member}'  AND is_delete = 0
    ORDER BY create_date DESC";
    }

    $rs = $mysqli->query($sql);
    if ( $rs->num_rows ) {
        $row = $rs->fetch_assoc();
        if ( $row['OTP'] == $OTP || $row['OTP'] == $dataObject->OTP ) {
            $end_date = date("Y-m-d H:i:s", strtotime($row['OTP_End']));
            if ( $end_date >= date("Y-m-d H:i:s") ) {
                $data['status'] = 1;
                $data['account_id'] = $account_id;
            } else {              
                $data['responseText'] =  'OTP หมดอายุตั้งแต่ '.date("d-m-Y H:i:s",strtotime($end_date)).' น.';
            }
        } else {
            $data['responseText'] =  'OTP ไม่ตรงกัน';
        }
    } else {
        $data['responseText'] =  'ไม่พบข้อมูลรหัส OTP นี้ในระบบ';
    }

    echo json_encode($data); 
    exit();
?>