<?php
function U2T($text) { return @iconv("UTF-8", "TIS-620//IGNORE", ($text)); }

$pdf = new FPDI('P');

$pdf->AddFont('THSarabunNew', '', 'THSarabunNew.php');
$pdf->AddFont('THSarabunNew', 'B', 'THSarabunNew-Bold.php');

$pdf->SetMargins(0, 0, 0);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetAutoPageBreak(false);

$pdf->AddPage();

$border = 0;
$line_h = 5;
$x = 10;
$y = 10;

$pdf->SetFont('THSarabunNew', 'B', 16);

$pdf->SetXY($x, $y);
$pdf->Cell(0, $line_h, U2T($_SESSION['COOP_NAME']), $border, 1, "C");
$y += $line_h + 1;
$pdf->SetXY($x, $y);
$pdf->Cell(0, $line_h, U2T($row_meeting['meeting_name']), $border, 1, "C");
$y += $line_h + 1;
$pdf->SetXY($x, $y);
$pdf->Cell(0, $line_h, U2T("วันที่ ".$this->center_function->ConvertToThaiDate($row_meeting['meeting_date'],1,0)), $border, 1, "C");

$pdf->SetFont('THSarabunNew', 'B', 14);

$cols = [15, 20, 28, 30, 35, 40, 30, 30];

$y += $line_h + 5;
$pdf->SetXY($x, $y);
$pdf->Cell($cols[0], $line_h, U2T("ลำดับ"), 1, 0, "C");
$pdf->Cell($cols[1], $line_h, U2T("รหัสสมาชิก"), 1, 0, "C");
$pdf->Cell($cols[2] + $cols[3], $line_h, U2T("ชื่อ - นามสกุล"), 1, 0, "C");
$pdf->Cell($cols[4], $line_h, U2T("วันที่/เวลาลงทะเบียน"), 1, 0, "C");
//$pdf->Cell($cols[5], $line_h, U2T("การลงทะเบียน"), 1, 0, "C");
$pdf->Cell($cols[6], $line_h, U2T("เลขหางบัตร"), 1, 0, "C");
$pdf->Cell($cols[7], $line_h, U2T("ของทีระลึก"), 1, 1, "C");

$pdf->SetFont('THSarabunNew', '', 14);

if(!empty($data)) {
	foreach($data as $key => $row) {
		if($y > 280) {
			$pdf->AddPage();
			$y = 10;
		}
		else {
			$y += $line_h;
		}

		$regis_type = '';
		if( $row['facescan_id'] ) $regis_type = 'ใบหน้า';
		elseif( $row['id_card_data'] ) $regis_type = 'บัตรประชาชน';
		else $regis_type = 'เลขสมาชิก';

		$x = 10;
		$pdf->SetXY($x, $y);
		$pdf->Cell($cols[0], $line_h, U2T(number_format($key + 1)), 1, 0, "C");
		$pdf->Cell($cols[1], $line_h, U2T($row['member_id']), 1, 0, "C");
		$pdf->Cell($cols[2] + $cols[3], $line_h, U2T($row['firstname_th'].' '.$row['lastname_th']), 1, 0, "L");
		$pdf->Cell($cols[4], $line_h, U2T($this->center_function->ConvertToThaiDate($row['create_time'])), 1, 0, "C");
		//$pdf->Cell($cols[5], $line_h, U2T($regis_type), 1, 0, "C");
		$pdf->Cell($cols[6], $line_h, U2T($row['card_tail_number']), 1, 0, "C");
		$pdf->Cell($cols[7], $line_h, U2T($row['is_gift'] ? "/" : ''), 1, 1, "C");
	}
}

$pdf->Output();