<?php
	function U2T($text) { return @iconv("UTF-8", "TIS-620//IGNORE", ($text)); }
	function num_format($text) {
		if($text!=''){
			return number_format($text,2);
		}else{
			return '';
		}
	}
	
	//$pdf->grid = 10;	
	
	//หนังสือเงินฉุกเฉิน
	$filename = $_SERVER["DOCUMENT_ROOT"].PROJECTPATH."/assets/document/loan/bb_coop/book_emergent.pdf";
	$pageCount = $pdf->setSourceFile($filename);
	for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {	
	$pdf->AddPage();
		$tplIdx = $pdf->importPage($pageNo); 
		$pdf->useTemplate($tplIdx, 0, 0, 0, 0, true);
		
		$pdf->AddFont('THSarabunNew', '', 'THSarabunNew.php');
		$pdf->SetFont('THSarabunNew', '', 13 );
		
		$border = 0;
		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetAutoPageBreak(true,0);
		
		$arr_marry_status = array('1'=>'โสด', '2'=>'สมรส','3'=>'หย่า ','4'=>'หม้าย');
		if($pageNo == '1'){	
			$y_point = 20.5;
			$pdf->SetXY( 32, $y_point );
			$pdf->MultiCell(35, 5, U2T(""), $border, 1);	
			
			$y_point = 27;
			$pdf->SetXY( 32, $y_point );
			$pdf->MultiCell(35, 5, U2T(""), $border, 1);	
			
			$y_point = 22;
			$pdf->SetXY( 167, $y_point );
			$pdf->MultiCell(30, 5, U2T($data['contract_number']), $border, 1);
			
			$y_point = 29;
			$pdf->SetXY( 159, $y_point );
			$pdf->MultiCell(31, 5, U2T($this->center_function->ConvertToThaiDate($data['createdatetime'],0,0)), $border, 'C');			
			
			$y_point = 58;
			$pdf->SetXY( 155, $y_point );
			$pdf->MultiCell(39, 5, U2T($write_at), $border, 'C');
			
			$y_point = 64.5;
			$pdf->SetXY( 155, $y_point );
			$pdf->MultiCell(39, 5, U2T($this->center_function->ConvertToThaiDate($data['createdatetime'],0,0)), $border, 'C');			
			
			$y_point = 78;
			$pdf->SetXY( 48, $y_point );
			$pdf->MultiCell(145, 5, U2T($full_name), $border, 1);
			
			$y_point = 85.5;
			$pdf->SetXY( 105, $y_point );
			$pdf->MultiCell(49, 5, U2T($member_id), $border, 'C');
			
			$y_point = 93.5;
			$pdf->SetXY( 38, $y_point );
			$pdf->MultiCell(102, 5, U2T($position), $border, 1);	
			
			$y_point = 101;
			$pdf->SetXY( 30, $y_point );
			$pdf->MultiCell(46, 5, U2T($data['id_card']), $border, 1);
			$pdf->SetXY(85, $y_point );
			$pdf->MultiCell(110, 5, U2T($level), $border, 1);
			
			$y_point = 109;
			$pdf->SetXY( 50, $y_point );
			$pdf->MultiCell(140, 5, U2T($c_address_no.$c_address_moo.$c_address_village.$c_address_soi.$c_address_road), $border, 1);
			
			$y_point = 116.5;
			$pdf->SetXY( 30, $y_point );
			$pdf->MultiCell(35, 5, U2T($c_district_name), $border, 1);
			$pdf->SetXY( 77, $y_point );
			$pdf->MultiCell(50, 5, U2T($c_amphur_name), $border, 1);
			$pdf->SetXY( 137, $y_point );
			$pdf->MultiCell(60, 5, U2T($c_province_name), $border, 1);
			
			$y_point = 124;
			$pdf->SetXY( 41, $y_point );
			$pdf->MultiCell(23, 5, U2T($c_zipcode), $border, 1);
			$pdf->SetXY( 85, $y_point );
			$pdf->MultiCell(33, 5, U2T($data['office_tel']), $border, 1);
			$pdf->SetXY( 137, $y_point );
			$pdf->MultiCell(60, 5, U2T($data['mobile']), $border, 1);
			
			$y_point = 139.5;
			$pdf->SetXY( 88, $y_point );
			$pdf->MultiCell(93, 5, U2T(num_format($data['loan_amount'])), $border, 'C');
			
			$y_point = 147;
			$pdf->SetXY( 22, $y_point );
			$pdf->MultiCell(118, 5, U2T($this->center_function->convert($data['loan_amount'])), $border, 'C');
			
			$y_point = 162.5;
			$pdf->SetXY( 30, $y_point );
			$pdf->MultiCell(160, 5, U2T($data['loan_reason']), $border, 1);
			
			$y_point = 170;			
			$pdf->SetXY( 143, $y_point );
			$pdf->MultiCell(50, 5, U2T(($paid_per_month != '')?num_format($paid_per_month):''), $border, 'C');
			
			$y_point = 178;			
			$pdf->SetXY( 23, $y_point );
			$pdf->MultiCell(117, 5, U2T(($paid_per_month != '')?$this->center_function->convert($paid_per_month):''), $border, 'C');
						
			$y_point = 185.5;
			$pdf->SetXY( 40, $y_point );
			$pdf->MultiCell(25, 5, U2T($data['period_amount']), $border, 'C');	
			$pdf->SetXY( 90, $y_point );
			$pdf->MultiCell(36, 5, U2T($this->center_function->ConvertToThaiDate($data['date_start_period'],0,0)), $border, 'C');	
		}else if($pageNo == '2'){			
			$y_point = 73.5;
			$pdf->SetXY( 130, $y_point );
			$pdf->MultiCell(48, 5, U2T($full_name), $border, 'C');
		 }
	 }

	$pdf->Output();