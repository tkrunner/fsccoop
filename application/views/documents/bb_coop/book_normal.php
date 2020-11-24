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
	//หนังสือเงินกู้สามัญ
	$filename = $_SERVER["DOCUMENT_ROOT"].PROJECTPATH."/assets/document/loan/bb_coop/book_normal.pdf";
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
			$y_point = 34;
			$pdf->SetXY( 52, $y_point );
			$pdf->MultiCell(49, 5, U2T($data['contract_number']), $border, 1);
			
			$y_point = 48;
			$pdf->SetXY( 160, $y_point );
			$pdf->MultiCell(30, 5, U2T($this->center_function->ConvertToThaiDate($data['createdatetime'],0,0)), $border, 'C');			
			
			$y_point = 61.5;
			$pdf->SetXY( 47, $y_point );
			$pdf->MultiCell(142, 5, U2T($full_name), $border, 1);
			
			$y_point = 68;
			$pdf->SetXY( 120, $y_point );
			$pdf->MultiCell(30, 5, U2T($member_id), $border, 'C');
			
			$y_point = 74.5;
			$pdf->SetXY( 75, $y_point );
			$pdf->MultiCell(115, 5, U2T($position), $border, 1);
			
			$y_point = 81.5;
			$pdf->SetXY( 91, $y_point );
			$pdf->MultiCell(61, 5, U2T($data['id_card']), $border, 'C');

			$y_point = 88;			
			$pdf->SetXY( 36, $y_point );
			$pdf->MultiCell(78, 5, U2T($c_address_no.$c_address_moo.$c_address_village.$c_address_soi), $border, 1);
			$pdf->SetXY( 122, $y_point );
			$pdf->MultiCell(68, 5, U2T($c_address_road), $border, 1);
			
			$y_point = 94.5;
			$pdf->SetXY( 30, $y_point );
			$pdf->MultiCell(45, 5, U2T($c_district_name), $border, 1);
			$pdf->SetXY( 87, $y_point );
			$pdf->MultiCell(55, 5, U2T($c_amphur_name), $border, 1);
			$pdf->SetXY( 153, $y_point );
			$pdf->MultiCell(41, 5, U2T($c_province_name), $border, 1);
			
			$y_point = 101;
			$pdf->SetXY( 39, $y_point );
			$pdf->MultiCell(27, 5, U2T($c_zipcode), $border, 1);
			$pdf->SetXY( 82, $y_point );
			$pdf->MultiCell(45, 5, U2T($data['mobile']), $border, 1);
			
			$y_point = 115;
			$pdf->SetXY( 98, $y_point );
			$pdf->MultiCell(40, 5, U2T(num_format($data['loan_amount'])), $border, 'C');
			
			$y_point = 121.5;
			$pdf->SetXY( 19, $y_point );
			$pdf->MultiCell(110, 5, U2T($this->center_function->convert($data['loan_amount'])), $border, 'C');

			$y_point = 128;			
			$pdf->SetXY( 138, $y_point );
			$pdf->MultiCell(65, 5, U2T(($paid_per_month != '')?$this->center_function->convert($paid_per_month):''), $border, 'C');
			
			$y_point = 134.5;
			$pdf->SetXY( 79, $y_point );
			$pdf->MultiCell(47, 5, U2T($this->center_function->convert_number_to_text($data['interest_per_year'])), $border, 'C');	
			$pdf->SetXY( 155, $y_point );
			$pdf->MultiCell(40, 5, U2T($this->center_function->convert_number_to_text($data['period_amount'])), $border, 'C');	
			
			$y_point = 141;
			$pdf->SetXY( 78, $y_point );
			$pdf->MultiCell(125, 5, U2T(($paid_per_month_last != '')?$this->center_function->convert($paid_per_month_last):''), $border, 'C');	
			
			$y_point = 148;
			$pdf->SetXY( 33, $y_point );
			$pdf->MultiCell(42, 5, U2T($this->center_function->ConvertToThaiDate($date_start_period,0,0)), $border, 'C');			

		}else if($pageNo == '2'){		
			$y_point = 16.5;
			$pdf->SetXY( 112, $y_point );
			$pdf->MultiCell(40, 5, U2T($member_id), $border, 'C');
			$pdf->SetXY( 163, $y_point );
			$pdf->MultiCell(27, 5, U2T(num_format($data_share['share_collect'])), $border, 'C');
			
			$y_point = 23;
			$pdf->SetXY( 32, $y_point );
			$pdf->MultiCell(44, 5, U2T(($data_share['share_collect_value'] != '')?num_format($data_share['share_collect_value']):''), $border, 'C');		
			
			//คู่สมรส
			if(@$data['marry_name'] != ''){	
				$y_point = 121;
				$pdf->SetXY( 172, $y_point );
				$pdf->MultiCell(30, 5, U2T($this->center_function->ConvertToThaiDate($data['createdatetime'],0,0)), $border, 'C');			
				
				$y_point = 130.5;
				$pdf->SetXY( 41, $y_point );
				$pdf->MultiCell(63, 5, U2T($data['marry_name']), $border, 1);
				$pdf->SetXY( 137, $y_point );
				$pdf->MultiCell(65, 5, U2T($full_name), $border, 1);
			
				$y_point = 138.5;
				$pdf->SetXY( 46, $y_point );
				$pdf->MultiCell(96, 5, U2T($full_name), $border, 1);
			}
			
			$y_point = 177;
			$pdf->SetXY( 48, $y_point );
			$pdf->MultiCell(65, 5, U2T($full_name), $border, 1);
			$pdf->SetXY( 150, $y_point );
			$pdf->MultiCell(40, 5, U2T(num_format($data['loan_amount'])), $border, 'C');			
			
			$y_point = 184;
			$pdf->SetXY( 20, $y_point );
			$pdf->MultiCell(106, 5, U2T($this->center_function->convert($data['loan_amount'])), $border, 'C');
		 }	 
	 }

	$pdf->Output();