<?php
    define( 'API_ACCESS_KEY', 'AAAASxPbYAU:APA91bHnYzd06vU2Cgz1bElOVFkwkrg8bionKKYC_3jWLwz00dIbSE7OK-p7v6hDQe4XIbf0oTMPK6EB2iuYuygQ5zxUGA897C-pQ3UvcLfqj7et-DxiWRXxhVs_pL99wyoer_EScy_y' );

    #ENCRYPT_KEY
    define("ENCRYPT_KEY", "pPXJHE2@$2F6+Z*XCAwfr8UTn@Jw7b*U");

    function encrypt_text($plaintext) {
        $ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
        $iv = openssl_random_pseudo_bytes($ivlen);
        $ciphertext_raw = openssl_encrypt($plaintext, $cipher, ENCRYPT_KEY, $options=OPENSSL_RAW_DATA, $iv);
        $hmac = hash_hmac('sha256', $ciphertext_raw, ENCRYPT_KEY, $as_binary=true);
        $ciphertext = base64_encode($iv.$hmac.$ciphertext_raw);
        
        return $ciphertext;
    }
    
    function decrypt_text($ciphertext) {
        $c = base64_decode($ciphertext);
        $ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
        $iv = substr($c, 0, $ivlen);
        $hmac = substr($c, $ivlen, $sha2len=32);
        $ciphertext_raw = substr($c, $ivlen+$sha2len);
        $original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, ENCRYPT_KEY, $options=OPENSSL_RAW_DATA, $iv);
        $calcmac = hash_hmac('sha256', $ciphertext_raw, ENCRYPT_KEY, $as_binary=true);
        
        return hash_equals($hmac, $calcmac) ? $original_plaintext : "";
    }

    function firebaseCloudMessage($token, $title, $msg, $badge, $msg_id) {      
        #prep the bundle
        // $msg = array( 'title' => '', 'body'  => $title, 'sound' => 'default', 'badge' => $badge, 'click_action' => 'FCM_PLUGIN_ACTIVITY' );
        // $data_json = array( 'title' => $title, 'body' => $msg, 'badge' => $badge, 'msg_id' => $msg_id );
        // $fields = array( 'to' => $token, 'notification'	=> $msg, 'data' => $data_json, 'badge' => $badge );
        // $headers = array( 'Authorization: key=' . API_ACCESS_KEY, 'Content-Type:application/json' );

        $msg = array( 'title' => 'SPKT Co-op', 'body'  => $title, 'sound' => 'default', 'badge' => $badge, 'click_action' => 'FCM_PLUGIN_ACTIVITY' );
        $data_json = array( 'title' => $title, 'body' => $msg, 'badge' => $badge, 'msg_id' => $msg_id );
        $fields = array( 'to' => $token, 'notification'	=> $msg, 'data' => $data_json, 'badge' => $badge );
        $headers = array( 'Authorization: key=' . API_ACCESS_KEY, 'Content-Type:application/json' );

        #Send Reponse To FireBase Server	 
        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
        $result = curl_exec( $ch );
        curl_close($ch);
        return $result;
    }

    function send_sms($mobile, $msg) {
		// if (!class_exists('SendMessageService')) {
		// 	require  'sendMessageService.php';
		// }
 		
		// $account = 'post01@pghc';
		// $password = 'D51288E198AAB1324587442964C4FBC4424C2ED872CA4C0A6D69B0B501B3433A';


		// $mobile_no = $mobile ;
		// // or $mobile_no = '0830000000,0831111111';
		// $message = $msg ;
		// $category = 'General';
		// $sender_name = '';

		// $results = SendMessageService::sendMessage($account, $password, $mobile_no, $message, '', $category, $sender_name);
 		// return $results ; 
	 
		
		
		
		$Username	= "upbean";
		$Password	= "up69Bean";
		$Sender		= "Upbean";
        $Message	= urlencode(iconv("UTF-8", "TIS-620", $msg));
        $Parameter  = "User={$Username}&Password={$Password}&Msnlist={$mobile}&Msg={$Message}&Sender={$Sender}";
		$API_URL	= "http://member.smsmkt.com/SMSLink/SendMsg/index.php";

		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,$API_URL);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_POST,1);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$Parameter);

		$result = curl_exec($ch);
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		return $result;
		
    }

    function send_sms_by_spktcoop($mobile, $msg) {
		$Username	= "upbean";
		$Password	= "up69Bean";
		$Sender		= "SPKTCOOP";
        $Message	= urlencode(iconv("UTF-8", "TIS-620", $msg));
        $Parameter  = "User={$Username}&Password={$Password}&Msnlist={$mobile}&Msg={$Message}&Sender={$Sender}";
		$API_URL	= "http://member.smsmkt.com/SMSLink/SendMsg/index.php";

		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,$API_URL);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_POST,1);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$Parameter);

		$result = curl_exec($ch);
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		return $result;
    }

    function get_token($member_id) {
        return md5($member_id."-".date("Ymdhis"));
    }

    function date2thaiformat($d) { //2018-01-01 or 2018-01-01 12:12:12 to 01-01-2561
        return  explode("-" , explode(" ", $d)[0])[2]."-".explode("-" , explode(" ", $d)[0])[1]."-".(string)((int)explode("-" ,explode(" ", $d)[0])[0] + 543);
    }

    function dateDB2thaidate($date, $monthShort = true, $yearShort = true, $time = true) { //2018-01-01 or 2018-01-01 12:12:12
        if( !$monthShort ) {
            $month = array( 1 => "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม" );
        } else {
            $month = array( 1 => "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค." );
        }
        $dateTime = explode(" ", $date);
        $date = explode("-", $dateTime[0]);
        $y = $date[0];
        $m = $date[1];
        $d = $date[2];
        $yConvert = ( $yearShort ) ? substr((string)((int)$y + 543), 2) : (string)((int)$y + 543);
        $dateConvert = $d." ".$month[(int)$m]." ".$yConvert;
        $convert = ( $time ) ? $dateConvert." ".$dateTime[1] : $dateConvert;
        return $convert;
    }
    
    function calcDate($d) {
        $birthday = $d;      //รูปแบบการเก็บค่าข้อมูลวันเกิด
        $today = date("Y-m-d");   //จุดต้องเปลี่ยน  
        list($byear, $bmonth, $bday) = explode("-",$birthday);       //จุดต้องเปลี่ยน
        list($tyear, $tmonth, $tday) = explode("-",$today);                //จุดต้องเปลี่ยน    
        $mbirthday = mktime(0, 0, 0, $bmonth, $bday, $byear); 
        $mnow = mktime(0, 0, 0, $tmonth, $tday, $tyear );
        $mage = ($mnow - $mbirthday);      
        // echo "วันเกิด $birthday"."<br>\n";
        // echo "วันที่ปัจจุบัน $today"."<br>\n";    
        // echo "รับค่า $mage"."<br>\n";
        $u_y = date("Y",$mage)-1970;
        $u_m = date("m",$mage)-1;
        $u_d = date("d",$mage)-1;    
        return $u_y.' ปี '.$u_m.' เดือน '.$u_d.' วัน';
    }

    function getMonths($n, $is_short = false) {
        if( !$is_short ) {
            $month = array( 1 => "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม" );
        } else {
            $month = array( 1 => "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค." );
        }   
        return $month[$n];
    }

    function NumberToChar($number){
        $txtnum1 = array('ศูนย์','หนึ่ง','สอง','สาม','สี่','ห้า','หก','เจ็ด','แปด','เก้า','สิบ');
        $txtnum2 = array('','สิบ','ร้อย','พัน','หมื่น','แสน','ล้าน','สิบ','ร้อย','พัน','หมื่น','แสน','ล้าน');
        $number = str_replace(",","",$number);
        $number = str_replace(" ","",$number);
        $number = str_replace("บาท","",$number);
        $number = explode(".",$number);
        if(sizeof($number)>2){
            return 'ทศนิยมหลายตัวนะจ๊ะ';
            exit;
        }
        $strlen = strlen($number[0]);
        $convert = '';
        for($i=0;$i<$strlen;$i++){
            $n = substr($number[0], $i,1);
            if($n!=0){
                if($i==($strlen-1) AND $n==1){ $convert .= 'เอ็ด'; }
                elseif($i==($strlen-2) AND $n==2){  $convert .= 'ยี่'; }
                elseif($i==($strlen-2) AND $n==1){ $convert .= ''; }
                else{ $convert .= $txtnum1[$n]; }
                $convert .= $txtnum2[$strlen-$i-1];
            }
        }

        $convert .= 'บาท';
        if($number[1]=='0' OR $number[1]=='00' OR
            $number[1]==''){
            $convert .= 'ถ้วน';
        }else{
            $strlen = strlen($number[1]);
            for($i=0;$i<$strlen;$i++){
                $n = substr($number[1], $i,1);
                if($n!=0){
                    if($i==($strlen-1) AND $n==1) {
                        $convert .= 'เอ็ด';
                    } elseif($i==($strlen-2) AND $n==2) {
                        $convert .= 'ยี่';
                    } elseif($i==($strlen-2) AND $n==1) {
                        $convert .= '';
                    } else {
                        $convert .= $txtnum1[$n];
                    }
                    $convert .= $txtnum2[$strlen-$i-1];
                }
            }
            $convert .= 'สตางค์';
        }
        return $convert;
    }

    function urlsafeB64Decode($input){

        $remainder = strlen($input) % 4;
        if ($remainder) {
            $padlen = 4 - $remainder;
            $input .= str_repeat('=', $padlen);
        }
        return base64_decode(strtr($input, '-_', '+/'));
    }

    function urlsafeB64Encode($input)
    {
        return str_replace('=', '', strtr(base64_encode($input), '+/', '-_'));
    }

    function DateThai($strDate)
	{

		// $strYear = date("Y",strtotime($strDate))+543;
		$strMonth= $strDate;
		// $strDay= date("j",strtotime($strDate));
		// $strHour= date("H",strtotime($strDate));
		// $strMinute= date("i",strtotime($strDate));
		// $strSeconds= date("s",strtotime($strDate));
		$strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
		$strMonthThai=$strMonthCut[$strMonth];
		return "$strMonthThai";
	}

?>
