<?php
function U2T($text) { return @iconv("UTF-8", "TIS-620//IGNORE", ($text)); }
function num_format($text) {
    if($text!=''){
        return number_format($text,2);
    }else{
        return '';
    }
}

    // $filename = $_SERVER["DOCUMENT_ROOT"]."/assets/document/loan/emc-project-loan.pdf" ;
    $filename = $_SERVER["DOCUMENT_ROOT"]."/fsccoop/assets/document/loan/emc-project-loan.pdf" ;
    // echo $filename;exit;
	$pdf = new FPDI();

    $pageCount_1 = $pdf->setSourceFile($filename);
    $myImage = "assets/images/check-mark.png";
    $full_data = $loan_fscoop;
    $location  = $profile_location; // location ของสถานที่ ex เขียนที่.....

    $age                = $this->center_function->diff_birthday($full_data['birthday']); //อายุ
    $monthtext          = $this->center_function->month_arr(); // function แปลงเดือนเป็นตัวอักษร
    $money_loan_amount_2text = $this->center_function->convert($full_data['loan_amount']); //จำนวนเงินกู็(ตัวอักษร)
    $money_salary_2text = $this->center_function->convert($full_data['salary']);//เงินเดือน(ตัวอักษร)
    $start_member_year  = $this->center_function->diff_year($full_data['approve_date'],date('Y-m-d H:i:s')); // ปีที่เริ่มทำงาน (จำนวนปี)
    $start_member_month       = $this->center_function->diff_month_interval($full_data['approve_date'],date('Y-m-d H:i:s')); // จำนวนเดือน
    if ($full_data['approve_date'] != ''){
        $date_to_year       = (substr($full_data['approve_date'], 0, 4))+543; // ปีที่เริ่มทำสัญญา
    }
    $date_to_text       = number_format(substr($full_data['approve_date'], 8, 2)); // วันที่เริ่มทำสัญญา
    $date_to_month      = number_format(substr($full_data['approve_date'], 5, 2)); // เดือนที่เริ้มทำสัญญา
    $month2text         = $monthtext[$date_to_month]; // เดือนที่เริ่มทำสัญญา (ตัวอักษร)
    $full_date          = $date_to_text."  ".$month2text."  ".$date_to_year; // วัน:เดือน:ปี ที่เริ่มทำสัญญา
    if ($full_data['createdatetime'] != ''){
        $create_year       = (substr($full_data['createdatetime'], 0, 4))+543; // // ปีที่บันทึกข้อมูล
    }
    $create_day = number_format(substr($full_data['createdatetime'], 8, 2)); // วันที่บันทึกข้อมูล
    $create_month = number_format(substr($full_data['createdatetime'], 5, 2)); // เดือนที่บันทึกข้อมูล
    $create_month2text = $monthtext[$create_month]; // เดือนที่บันทึกข้อมูล(ตัวอักษร)
    if ($full_data['createdatetime'] != ''){
        $create_year       = (substr($full_data['createdatetime'], 0, 4))+543; // // ปีที่บันทึกข้อมูล
    }
    $day_start_period   = number_format(substr($full_data['date_start_period'], 8, 2));// วันเริ่มจ่ายงวด(หุ้น)
    $month_start_period = number_format(substr($full_data['date_start_period'], 5, 2)); // เดือนที่จ่ายค่างวด(หุ้น)
    $year_start_period  = (substr($full_data['approve_date'], 0, 4))+543; // ปีที่จ่ายค่างวด(หุ้น)
    $full_start_period  = $day_start_period."  ".$month_start_period."  ".$year_start_period; // วัน, เดือน, ปี ที่จ่ายค่างวด(หุ้น)
    $fullname_th        = $full_data['prename_full'].$full_data['firstname_th']."  ".$full_data['lastname_th']; // คำนำหน้าชื่อ , ชื่อ-สกุล (ผู้กู้)
    $contract_number_font = substr($full_data['contract_number'], 0, -8); // ตัวอักษรหน้า เลขที่สัญญา ex. ฉฉ999999 = ฉฉ
    $contract_number_back = substr($full_data['contract_number'], -9);   //ตัวอักษรหลัง เลขที่สัญญา ex. 999999 = ฉฉ
    $period_amount = substr($this->center_function->convert($full_data['period_amount']),0,-3*7); //งวด(ตัวอักษร)
	for ($pageNo = 1; $pageNo <= $pageCount_1; $pageNo++) {	
        $pdf->AddPage();
            $tplIdx = $pdf->importPage($pageNo);
            $pdf->useTemplate($tplIdx, 0, 0, 0, 0, true);
            $pdf->AddFont('THSarabunNew', '', 'THSarabunNew.php');
            $pdf->SetFont('THSarabunNew', '', 14 );
            
            // $pdf->SetTitle(U2T('คำขอกู้เงินเพื่อการศึกษา'));
            $border = isset($_GET['show']) && $_GET['show'] == '1' ?  1 : 0;
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetAutoPageBreak(true,0);
            if($pageNo == '1'){
                // $pdf->Image($myImage, 35.8, 162.3, 3);
                $y_point = 27.8;
                $pdf->SetFont('THSarabunNew', '', 10 );
                $pdf->SetXY(184, $y_point);
                $pdf->MultiCell(8, 5, U2T($contract_number_font), $border, "C");
                $pdf->SetXY(194, $y_point);
                $pdf->MultiCell(12, 5, U2T($contract_number_back), $border, "L");
                $pdf->SetFont('THSarabunNew', '', 14 );
                $y_point = 33.5;
                $pdf->SetXY(176, $y_point);
                $pdf->MultiCell(8, 5, U2T($date_to_text), $border, "C"); // วันที่ (วัน)
                $pdf->SetXY(186, $y_point);
                $pdf->MultiCell(8, 5, U2T($date_to_month), $border, "C"); // วันที่ (เดือน)
                $pdf->SetXY(196, $y_point);
                $pdf->MultiCell(10, 5, U2T($date_to_year), $border, "C"); // วันที่ (ปี)
                $y_point = 51.2;
                $pdf->SetXY( 32.5, $y_point );
                $pdf->MultiCell(62, 5, U2T($location['profile_location']['0']['coop_name_th']), $border, "C"); //เขียนที่
                $y_point = 58;
                $pdf->SetXY( 130.2, $y_point );
                $pdf->MultiCell(11, 5, U2T($create_day), $border, "C"); ///วันที่ 
                $pdf->SetXY( 148.9, $y_point );
                $pdf->MultiCell(22.9, 5, U2T($create_month2text), $border, "C"); //เดือนที่
                $pdf->SetXY( 177.5, $y_point );
                $pdf->MultiCell(15, 5, U2T($create_year), $border, "C"); //ปี
                $y_point = 80.2;
                $pdf->SetXY( 45.5, $y_point );
                $pdf->MultiCell(61, 5, U2T($fullname_th), $border, "C"); //ชื่อผู้กู้
                $pdf->SetXY( 136.5, $y_point );
                $pdf->MultiCell(48, 5, U2T($full_data['member_id']), $border, "C"); //สมาชิกเลขทะเบียนที่
                $y_point = 87;
                $pdf->SetXY( 83, $y_point );
                $pdf->MultiCell(40, 5, U2T($full_data['position_name']), $border, "C"); //ชื่อผู้กู้
                $pdf->SetXY( 134, $y_point );
                $pdf->MultiCell(29.5, 5, U2T($full_data['mem_group_name']), $border, "C"); //สมาชิกเลขทะเบียนที่
                $pdf->SetXY( 181, $y_point );
                $pdf->MultiCell(15, 5, U2T($full_data['office_tel']), $border, "C"); //สมาชิกเลขทะเบียนที่
                $y_point = 94;
                $pdf->SetXY( 31, $y_point );
                $pdf->MultiCell(32, 5, U2T($full_data['mobile']), $border, "C"); //ชื่อผู้กู้
                $pdf->SetXY( 92, $y_point );
                $pdf->MultiCell(18, 5, U2T(number_format($full_data['salary'])), $border, "C"); //สมาชิกเลขทะเบียนที่
                $pdf->SetXY( 158, $y_point );
                $pdf->MultiCell(24, 5, U2T($full_data['']."*"), $border, "C"); //สมาชิกเลขทะเบียนที่
                $y_point = 101;
                $pdf->SetXY(22, $y_point);
                $pdf->MultiCell(17, 5, U2T($month2text), $border, "C");
                $pdf->SetXY(46.5, $y_point);
                $pdf->MultiCell(21, 5, U2T($date_to_year), $border, "C");
                $y_point = 108.1;
                $pdf->SetXY( 156.5, $y_point );
                $pdf->MultiCell(32, 5, U2T($full_data['loan_amount']), $border, "C"); //จำนวนเงินกู้
                $y_point = 114.7;
                $pdf->SetXY( 23, $y_point );
                $pdf->MultiCell(48, 5, U2T($money_loan_amount_2text), $border, "C"); //จำนวนเงินรวมที่ขอกู้(ตัวอักษร)
                $pdf->SetXY( 90, $y_point );
                $pdf->MultiCell(88, 5, U2T($full_data['loan_reason']), $border, "C"); //เหตุผลที่ขอกู้
                $y_point = 164.6;
                    $pdf->SetXY( 105.5, $y_point );
                    $pdf->MultiCell(36.8, 5, U2T($full_data['']."**"), $border, "C"); //ชื่อผู้กู้
                    $pdf->SetXY( 143.6, $y_point );
                    $pdf->MultiCell(26.5, 5, U2T($full_data['']."*"), $border, "C"); //ชื่อผู้กู้
                    $pdf->SetXY( 172.6, $y_point );
                    $pdf->MultiCell(25, 5, U2T($full_data['']."*"), $border, "C"); //ชื่อผู้กู้
                $y_point = 173.9;
                    $pdf->SetXY( 105.5, $y_point );
                    $pdf->MultiCell(36.8, 5, U2T($full_data['']."*"), $border, "C"); //ชื่อผู้กู้
                    $pdf->SetXY( 143.6, $y_point );
                    $pdf->MultiCell(26.5, 5, U2T($full_data['']."*"), $border, "C"); //ชื่อผู้กู้
                    $pdf->SetXY( 172.6, $y_point );
                    $pdf->MultiCell(25, 5, U2T($full_data['']."*"), $border, "C"); //ชื่อผู้กู้
                $y_point = 183.8;
                    $pdf->SetXY( 105.5, $y_point );
                    $pdf->MultiCell(36.8, 5, U2T($full_data['']."*"), $border, "C"); //ชื่อผู้กู้
                    $pdf->SetXY( 143.6, $y_point );
                    $pdf->MultiCell(26.5, 5, U2T($full_data['']."*"), $border, "C"); //ชื่อผู้กู้
                    $pdf->SetXY( 172.6, $y_point );
                    $pdf->MultiCell(25, 5, U2T($full_data['']."*"), $border, "C"); //ชื่อผู้กู้
                $y_point = 192.1;
                    $pdf->SetXY( 105.5, $y_point );
                    $pdf->MultiCell(36.8, 5, U2T($full_data['']."*"), $border, "C"); //ชื่อผู้กู้
                    $pdf->SetXY( 143.6, $y_point );
                    $pdf->MultiCell(26.5, 5, U2T($full_data['']."*"), $border, "C"); //ชื่อผู้กู้
                    $pdf->SetXY( 172.6, $y_point );
                    $pdf->MultiCell(25, 5, U2T($full_data['']."*"), $border, "C"); //ชื่อผู้กู้
                $y_point = 209.5;
                    $pdf->SetXY( 115.5, $y_point );
                    $pdf->MultiCell(25.8, 5, U2T($full_data['money_per_period']), $border, "C"); //ชื่อผู้กู้
                    $pdf->SetXY( 162.6, $y_point );
                    $pdf->MultiCell(15.5, 5, U2T($full_data['period_amount']), $border, "C"); //ชื่อผู้กู้
                    $y_point = 237.5;
                    $pdf->SetXY( 139.5, $y_point );
                    $pdf->MultiCell(40, 5, U2T($fullname_th), $border, "C"); //ชื่อผู้กู้
                    $y_point = 272.5;
                    $pdf->SetXY( 132.5, $y_point );
                    $pdf->MultiCell(40, 5, U2T($fullname_th), $border, "C"); //ชื่อผู้กู้
                // $y_point = 243.2;
                //     $pdf->SetXY( 131, $y_point );
                //     $pdf->MultiCell(55, 5, U2T($fullname_th), $border, "C"); //ชื่อผู้กู้
                // $y_point = 279.9;
                //     $pdf->SetXY( 131.5, $y_point );
                //     $pdf->MultiCell(46, 5, U2T($fullname_th), $border, "C"); //ชื่อผู้กู้
 
            }else if($pageNo == '2'){
                $y_point = 34;
                $pdf->SetXY( 130, $y_point );
                $pdf->MultiCell(33, 5, U2T($full_date), $border, "L"); //ชื่อผู้กู้
                $y_point = 60.8;
                $pdf->Image($myImage, 67.5, $y_point, 3);
                $pdf->Image($myImage, 106.2, $y_point, 3);
                $y_point = 74.8;
                $pdf->Image($myImage, 67.8, $y_point, 3);
                $pdf->Image($myImage, 106.2, $y_point, 3);
                $y_point = 88.8;
                $pdf->Image($myImage, 67.8, $y_point, 3);
                $pdf->Image($myImage, 106.2, $y_point, 3);
                $y_point = 185.8;
                $pdf->SetXY( 9.7, $y_point );
                $pdf->MultiCell(17.5 , 5, U2T('*'), $border, "C");//เงินเดือน
                $pdf->SetXY( 27.2, $y_point );
                $pdf->MultiCell(20 , 5, U2T('*'), $border, "C");//เงินค่าหุ้น(บาท)
                $pdf->SetXY( 47.3, $y_point );
                $pdf->MultiCell(20 , 5, U2T('*'), $border, "C");//จำกัดวงเงินกู้(บาท)
                $pdf->SetXY( 67.4, $y_point );
                $pdf->MultiCell(17.4, 5, U2T('*'), $border, "C");// น/สกู้ (สามัญ)
                $pdf->SetXY( 84.8, $y_point );
                $pdf->MultiCell(15 , 5, U2T('*'), $border, "C");// จำนวนเงิน
                $pdf->SetXY( 99.7, $y_point );
                $pdf->MultiCell(17.5 , 5, U2T('*'), $border, "C");// น/สกู้(ฉุกเฉิน)
                $pdf->SetXY( 117.2, $y_point );
                $pdf->MultiCell(17.5 , 5, U2T('*'), $border, "C");// จำนวนเงิน
                $pdf->SetXY( 134.8, $y_point );
                $pdf->MultiCell(17.4 , 5, U2T('*'), $border, "C");// จำนวนเงิน
                $pdf->SetXY( 152.3, $y_point );
                $pdf->MultiCell(14.8 , 5, U2T('*'), $border, "C");// จำนวนเงิน
                $pdf->SetXY( 167.2, $y_point );
                $pdf->MultiCell(12.5 , 5, U2T('*'), $border, "C");// จำนวนเงิน
                $pdf->SetXY( 179.8, $y_point );
                $pdf->MultiCell(24.9 , 5, U2T('*'), $border, "C");// จำนวนเงิน
                $y_point = 200;      
            }else if($pageNo == '3'){
                $y_point = 30;
                $pdf->SetXY( 169, $y_point );
                $pdf->MultiCell(10.5, 5, U2T($contract_number_font), $border, "C"); //ชื่อผู้กู้
                $pdf->SetXY( 183, $y_point );
                $pdf->MultiCell(10, 5, U2T($contract_number_back), $border, "C"); //ชื่อผู้กู้
                $y_point = 37.3;
                $pdf->SetXY( 163.5, $y_point );
                $pdf->MultiCell(34, 5, U2T($create_day." ".$create_month2text." ".$create_year), $border, "C"); //วันที่
                $y_point = 44.5;
                $pdf->SetXY( 44, $y_point );
                $pdf->MultiCell(142, 5, U2T($fullname_th), $border, "C"); //ชื่อผู้กู้
                $y_point = 51.5;
                $pdf->SetXY( 46, $y_point );
                $pdf->MultiCell(140, 5, U2T($fullname_th), $border, "C"); //ชื่อผู้กู้
                $y_point = 58.5;
                $pdf->SetXY( 152, $y_point );
                $pdf->MultiCell(34, 5, U2T($full_data['member_id']), $border, "C"); //รหัสสมาชิก
                $y_point = 65.2;
                $pdf->SetXY( 78, $y_point );
                $pdf->MultiCell(53, 5, U2T($full_data['position_name']), $border, "C"); //ตำแหน่ง
                $y_point = 72.2;
                $pdf->SetXY( 44.5, $y_point );
                $pdf->MultiCell(34, 5, U2T($full_data['id_card']), $border, "C"); //รหัสบัตรประชาชน
                $pdf->SetXY( 88, $y_point );
                $pdf->MultiCell(37, 5, U2T($full_data['mem_group_name_level']), $border, "C"); //สังกัด
                $pdf->SetXY( 158, $y_point );
                $pdf->MultiCell(25, 5, U2T($full_data['c_address_no']), $border, "C"); //บ้านเลนที่(ปัจจุบัน)
                $y_point = 79.1;
                $pdf->SetXY( 28.5, $y_point );
                $pdf->MultiCell(31, 5, U2T($full_data['c_address_road']), $border, "C"); //ถนน(ปัจจุบัน)
                $pdf->SetXY( 68.5, $y_point );
                $pdf->MultiCell(38, 5, U2T($full_data['district_name']), $border, "C"); //ตำบล(ปัจจุบัน)
                $pdf->SetXY( 115.5, $y_point );
                $pdf->MultiCell(27.5, 5, U2T($full_data['amphur_name']), $border, "C"); //อำเภอ(ปัจจุบัน)
                $pdf->SetXY( 151.5, $y_point );
                $pdf->MultiCell(34, 5, U2T($full_data['province_name']), $border, "C"); //จังหวัด (ปัจจุบัน)
                $y_point = 86;
                $pdf->SetXY( 34.5, $y_point );
                $pdf->MultiCell(42, 5, U2T($full_data['mobile']), $border, "C"); //โทรศัพท์มือถือ
                $y_point = 100;
                $pdf->SetXY( 100, $y_point );
                $pdf->MultiCell(25, 5, U2T($full_data['loan_amount']), $border, "C"); //จำนวนเงินกู้
                $pdf->SetXY( 133, $y_point );
                $pdf->MultiCell(53, 5, U2T($money_loan_amount_2text), $border, "C"); //จำนวนเงินกู้(ตัวอักษร)
                $y_point = 121;
                $pdf->SetXY( 104.7, $y_point );
                $pdf->MultiCell(74, 5, U2T($this->center_function->convert($full_data['money_per_period'])), $border, "C"); //เงินต้นละดอกเบี้ย(ตัวอักษร)
                $y_point = 128;
                $pdf->SetXY( 48, $y_point );
                $pdf->MultiCell(24, 5, U2T($this->center_function->convert($full_data['period_amount'])), $border, "C"); //งวดเงินกู้(ตัวอักษร)
                $pdf->SetXY( 143, $y_point );
                $pdf->MultiCell(34, 5, U2T($full_data['interest_per_year']), $border, "C"); //ชื่อผู้กู้
                $y_point = 135;
                $pdf->SetXY( 58, $y_point );
                $pdf->MultiCell(22, 5, U2T($monthtext[$month_start_period]), $border, "C"); //ชื่อผู้กู้
                $y_point = 266.5;
                $pdf->SetXY( 113.5, $y_point );
                $pdf->MultiCell(17, 5, U2T($full_data['']."*"), $border, "C"); //ชื่อผู้กู้
                $pdf->SetXY( 142, $y_point );
                $pdf->MultiCell(15, 5, U2T($full_data['']."*"), $border, "C"); //ชื่อผู้กู้
                $pdf->SetXY( 172, $y_point );
                $pdf->MultiCell(17, 5, U2T($full_data['']."*"), $border, "C"); //ชื่อผู้กู้
            }else if($pageNo == '4'){
                $y_point = 46.5;
                $pdf->SetXY( 123, $y_point );
                $pdf->MultiCell(62, 5, U2T($full_data['']."*"), $border, "C"); //ชื่อผู้กู้
                $y_point = 106.5;
                $pdf->SetXY( 125, $y_point );
                $pdf->MultiCell(46, 5, U2T($fullname_th), $border, "C"); //ชื่อผู้กู้
                $y_point = 113;
                $pdf->SetXY( 30, $y_point );
                $pdf->MultiCell(39, 5, U2T($full_data['']."*"), $border, "C"); //ชื่อผู้กู้
                $pdf->SetXY( 80, $y_point );
                $pdf->MultiCell(32, 5, U2T($full_data['position_name']), $border, "C"); //ชื่อผู้กู้
                $y_point = 119.5;
                $pdf->SetXY( 40, $y_point );
                $pdf->MultiCell(63, 5, U2T($fullname_th), $border, "C"); //ชื่อผู้กู้
                // $pdf->Image($myImage, 35.5, 154.3, 3);
                $y_point = 159;
                $pdf->SetXY( 157, $y_point );
                $pdf->MultiCell(32, 5, U2T($location['profile_location']['0']['coop_name_th']), $border, "C"); //ชื่อผู้กู้
                $y_point = 166.2;
                $pdf->SetXY( 155.5, $y_point );
                $pdf->MultiCell(35, 5, U2T($full_date), $border, "C"); //ชื่อผู้กู้
                $y_point = 173;
                $pdf->SetXY( 44, $y_point );
                $pdf->MultiCell(60, 5, U2T($full_data['marry_name']), $border, "C"); //ชื่อผู้กู้
                $pdf->SetXY( 127, $y_point );
                $pdf->MultiCell(53, 5, U2T($fullname_th), $border, "C"); //ชื่อผู้กู้
                $y_point = 179.8;
                $pdf->SetXY( 50, $y_point );
                $pdf->MultiCell(47, 5, U2T($fullname_th), $border, "C"); //ชื่อผู้กู้
                $y_point = 239.8;
                $pdf->SetXY( 33, $y_point );
                $pdf->MultiCell(86, 5, U2T($fullname_th), $border, "C"); //ชื่อผู้กู้
                $pdf->SetXY( 144, $y_point );
                $pdf->MultiCell(45, 5, U2T($fullname_th), $border, "C"); //ชื่อผู้กู้
                $y_point = 246.5;
                $pdf->SetXY( 24, $y_point );
                $pdf->MultiCell(82, 5, U2T($fullname_th), $border, "C"); //ชื่อผู้กู้
                $y_point = 264.5;
                $pdf->SetXY( 93.5, $y_point );
                $pdf->MultiCell(47, 5, U2T($fullname_th), $border, "C"); //ชื่อผู้กู้
            }else if($pageNo == '5'){
                $y_point = 50.3;
                $pdf->SetXY( 128.5, $y_point );
                $pdf->MultiCell(14, 5, U2T($date_to_text), $border, "C"); //วันที่(วัน)
                $pdf->SetXY( 150, $y_point );
                $pdf->MultiCell(25.5, 5, U2T($month2text), $border, "C"); //วันที่(เดือน)
                $pdf->SetXY( 182, $y_point );
                $pdf->MultiCell(15, 5, U2T($date_to_year), $border, "C"); //วันที่(ปี)
                $y_point = 84.1;
                $pdf->SetXY( 55.5, $y_point );
                $pdf->MultiCell(130, 5, U2T($fullname_th), $border, "C"); 
                $y_point = 91.1;
                $pdf->SetXY( 115.5, $y_point );
                $pdf->MultiCell(76, 5, U2T(number_format($full_data['loan_amount'])), $border, "C"); 
                $y_point = 97.7;
                $pdf->SetXY( 23.5, $y_point );
                $pdf->MultiCell(105, 5, U2T($this->center_function->convert($full_data['loan_amount'])), $border, "C"); 
                $y_point = 104.7;
                $pdf->SetXY( 125.5, $y_point );
                $pdf->MultiCell(44, 5, U2T($full_date), $border, "C"); 
                $y_point = 125.5;
                $pdf->SetXY( 136, $y_point );
                $pdf->MultiCell(60, 5, U2T($full_date), $border, "C"); 
                $y_point = 132.7;
                $pdf->SetXY( 38.5, $y_point );
                $pdf->MultiCell(155, 5, U2T($this->center_function->convert($full_data['loan_amount'])), $border, "C"); 
                $y_point = 148;
                $pdf->Image($myImage, 35.5, $y_point, 3);
                $y_point = 155;
                $pdf->Image($myImage, 35.5, $y_point, 3);
                $y_point = 162;
                $pdf->Image($myImage, 35.5, $y_point, 3);
                $y_point = 167.3;
                $pdf->SetXY( 35.5, $y_point );
                $pdf->MultiCell(146, 5, U2T('*'), $border, "C"); 
                $y_point = 174.2;
                $pdf->SetXY( 35.5, $y_point );
                $pdf->MultiCell(146, 5, U2T('*'), $border, "C"); 
                $y_point = 183.5;
                $pdf->Image($myImage, 35.5, $y_point, 3);
                $y_point = 187.5;
                $pdf->SetXY( 35.5, $y_point );
                $pdf->MultiCell(50, 5, U2T('*'), $border, "C");
                $pdf->SetXY( 102, $y_point );
                $pdf->MultiCell(80, 5, U2T('*'), $border, "C");
                $y_point = 196.5;
                $pdf->Image($myImage, 35.5, $y_point, 3);
                $y_point = 194.5;
                $pdf->SetXY( 47, $y_point );
                $pdf->MultiCell(133, 5, U2T('*'), $border, "C");
                $y_point = 201.5;
                $pdf->SetXY( 34, $y_point );
                $pdf->MultiCell(145, 5, U2T('*'), $border, "C");
                $y_point = 257;
                $pdf->SetXY( 85, $y_point );
                $pdf->MultiCell(48.5, 5, U2T($fullname_th), $border, "C");
            }else if($pageNo == '6'){
                $y_point = 43.5;
                $pdf->SetXY( 141.5, $y_point );
                $pdf->MultiCell(55, 5, U2T($location['profile_location']['0']['coop_name_th']), $border, "C"); //เขียนที่
                $y_point = 50;
                $pdf->SetXY( 127.5, $y_point );
                $pdf->MultiCell(12, 5, U2T($create_day), $border, "C"); //วัน
                $pdf->SetXY( 148.5, $y_point );
                $pdf->MultiCell(26, 5, U2T($create_month2text), $border, "C"); //เดือน
                $pdf->SetXY( 180,   $y_point );
                $pdf->MultiCell(14, 5, U2T($create_year), $border, "C"); //ปี
                $y_point = 61.3;
                $pdf->SetXY( 47, $y_point );
                $pdf->MultiCell(60, 5, U2T($fullname_th), $border, "C"); //ชื่อ...
                $pdf->SetXY( 145, $y_point );
                $pdf->MultiCell(33, 5, U2T($full_data['id_card']), $border, "C"); // เลขประจำตัวประชาชน
                $pdf->SetXY( 185, $y_point );
                $pdf->MultiCell(9, 5, U2T($age), $border, "C"); //อายุ
                $y_point = 68;
                $pdf->SetXY( 50.5, $y_point );
                $pdf->MultiCell(13, 5, U2T($full_data['c_address_no']), $border, "C"); //บ้านเลขที่(ปัจจุบัน)
                $pdf->SetXY( 68.5, $y_point );
                $pdf->MultiCell(15, 5, U2T(($full_data['c_address_moo']=='')?'-':$full_data['c_address_moo']), $border, "C"); // หมู่(ปัจจุบัน)
                $pdf->SetXY( 99.5, $y_point );
                $pdf->MultiCell(35, 5, U2T(($full_data['c_address_soi']=='')?'-':$full_data['c_address_soi']), $border, "C"); //ตรอก,ซอย (ปัจจุบัน)
                $pdf->SetXY( 142, $y_point );
                $pdf->MultiCell(50, 5, U2T(($full_data['c_address_road']=='')?'-':$full_data['c_address_road']), $border, "C"); // ถนน (ปัจจุบัน)
                $y_point = 75;
                $pdf->SetXY( 40.5, $y_point );
                $pdf->MultiCell(40, 5, U2T($full_data['district_name']), $border, "C"); // ตำบล(ปัจจุบัน)
                $pdf->SetXY( 96, $y_point );
                $pdf->MultiCell(38.5, 5, U2T($full_data['amphur_name']), $border, "C"); // อำเภอ (ปัจจุบัน)
                $pdf->SetXY( 143, $y_point );
                $pdf->MultiCell(50, 5, U2T($full_data['province_name']), $border, "C"); //จังหวัด(ปัจจุบัน)
                $y_point = 82;
                $pdf->SetXY( 46, $y_point );
                $pdf->MultiCell(32, 5, U2T($full_data['tel']), $border, "C"); // เบอร์โทรศัพท์
                $pdf->SetXY( 88.5, $y_point );
                $pdf->MultiCell(38, 5, U2T($full_data['mobile']), $border, "C"); // เบอร์มือถือ
                $pdf->SetXY( 140, $y_point );
                $pdf->MultiCell(53.5, 5, U2T($full_data['position_name'].'*'), $border, "C"); // ตำแหน่ง
                $y_point = 89;
                $pdf->SetXY( 30, $y_point );
                $pdf->MultiCell(32, 5, U2T('*'), $border, "C"); // 
                $pdf->SetXY( 96.5, $y_point );
                $pdf->MultiCell(22, 5, U2T(number_format($full_data['salary'])), $border, "C"); // 
                $pdf->SetXY( 127, $y_point );
                $pdf->MultiCell(67, 5, U2T($this->center_function->convert($full_data['salary'])), $border, "C"); // 
                $y_point = 96;
                $pdf->SetXY( 142, $y_point );
                $pdf->MultiCell(53, 5, U2T($full_data['member_id']), $border, "C"); // 
                $y_point = 117;
                $pdf->SetXY( 42, $y_point );
                $pdf->MultiCell(20, 5, U2T($full_data['contract_number']), $border, "C"); // 
                $pdf->SetXY( 88, $y_point );
                $pdf->MultiCell(18, 5, U2T($full_data['petition_number']), $border, "C"); // 
                $y_point = 225.3;
                $pdf->SetXY( 96, $y_point );
                $pdf->MultiCell(56, 5, U2T($fullname_th), $border, "C"); // 
            }
        }
	//exit;
	$pdf->Output();