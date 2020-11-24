<?php
 $nameStatus1 = '';
 $nameStatus2 = '';
if($index == 2){
  $account_id = '';
  $account_id2 = '';
  $nameStatus1 = 'หุ้นเดิม';
  $nameStatus2 = 'หุ้นใหม่';
  $accountName = $share_payable;
  $accountName2 = $share_collect;
}else{
  $nameStatus1 = 'จาก';
  $nameStatus2 = 'ไป';
}
if($index !=4){
$pdf->Ln(5); 
   $pdf->MultiCell(100, 0, '<img src="image/ok3.png" style="width:30px;margin-left:150px;" />', 0, 'C', 0, 0, '', '', true, 0, true, true, 0);
            $txt_sh = @$status_payment;
            $txt_sh1 = "11 ตุลาคม 15:30 น.";
            
            $pdf->Ln(16); 
            $pdf->SetFillColor(255, 255, 255);
           // $pdf->SetFontSize(25);
            $pdf->SetFont('thsarabun','B',25);
            $pdf->SetTextColor(10, 10, 10);
            $pdf->Cell(0, 0, $txt_sh, 0, 1, 'C', 0, 'B', 0);
            $pdf->Ln(0);
            // Removes bold
          $pdf->SetFont('');
            $pdf->SetFillColor(255, 255, 255);
            $pdf->SetFontSize(16);
            $pdf->SetTextColor(126, 124, 124);
            $pdf->Cell(0, 0, $transaction_time, 0, 1, 'C', 0, '', 0);
            $pdf->Ln(0);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->SetFontSize(25);
            $pdf->SetTextColor(10, 10, 10);
            $pdf->MultiCell(100, 0, '<span style="color:#1A864B"><b>'.$amount.'</b></span><span style="font-size:16px" > บาท</span>', 0, 'C', 0, 0, '', '', true, 0, true, true, 0);
    $pdf->Ln(14);
    // $header_left_column = '<img src="image/logo.png" style="width:70px;height:100px;margin-left:150px;"  />';
    //   $pdf->SetFillColor(255, 255, 255);
    //   // $pdf->MultiCell(15, 90, $header_left_column, 0, 'R', 1, 0, '', '', true, 0, true, true, 0);
    //   $pdf->MultiCell(100, 0, $header_left_column, 0, 'L', 0, 0, '', '', true, 0, true, true, 0);
    $pdf->SetFillColor(255, 255, 255);
    $pdf->SetFontSize(16);
    $pdf->SetTextColor(10, 10, 10);
    $pdf->MultiCell(3, 5, '', 0, 'L', 0, 0, '', '', true, 0, true, true, 0);
    $pdf->MultiCell(30, 0, 'รหัสอ้างอิง', 0, 'L', 0, 0, '', '', true, 0, true, true, 0);
    $pdf->MultiCell(65, 0, $transaction_id, 0, 'R', 0, 0, '', '', true, 0, true, true, 0);
    
    $pdf->Ln(11);
    $pdf->SetFillColor(255, 255, 255);
    $pdf->SetFontSize(16);
    $pdf->SetTextColor(10, 10, 10);
    $pdf->MultiCell(3, 5, '', 0, 'L', 0, 0, '', '', true, 0, true, true, 0);
    $pdf->MultiCell(30, 0, $nameStatus1, 0, 'L', 0, 0, '', '', true, 0, true, true, 0);
    $pdf->SetFont('thsarabun','B',16);
    $pdf->MultiCell(65, 0, '<span>'.$accountName.'</span>', 0, 'R', 0, 0, '', '', true, 0, true, true, 0);
    $pdf->SetFont('');
    $pdf->Ln(5);
    $pdf->MultiCell(98, 0, '<span>'.$account_id.'</span> ', 0, 'R', 0, 0, '', '', true, 0, true, true, 0);
    $pdf->Ln(11);
    $pdf->SetFillColor(255, 255, 255);
    $pdf->SetFontSize(16);
    $pdf->SetTextColor(10, 10, 10);
    $pdf->MultiCell(3, 5, '', 0, 'L', 0, 0, '', '', true, 0, true, true, 0);
    $pdf->MultiCell(30, 0, $nameStatus2 , 0, 'L', 0, 0, '', '', true, 0, true, true, 0);
    $pdf->SetFont('thsarabun','B',16);
    $pdf->MultiCell(65, 0, '<span>'.$accountName2.'</span>', 0, 'R', 0, 0, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->SetFont('');
    $pdf->MultiCell(98, 0, '<span>'.$account_id2.'</span> ', 0, 'R', 0, 0, '', '', true, 0, true, true, 0);
    $pdf->Ln(11);
    $pdf->SetFillColor(255, 255, 255);
    $pdf->SetFontSize(16);
    $pdf->SetTextColor(10, 10, 10);
    $pdf->MultiCell(3, 5, '', 0, 'L', 0, 0, '', '', true, 0, true, true, 0);
    $pdf->MultiCell(30, 0, 'ค่าธรรมเนียม', 0, 'L', 0, 0, '', '', true, 0, true, true, 0);
    $pdf->MultiCell(65, 0, '0.00 บาท', 0, 'R', 0, 0, '', '', true, 0, true, true, 0);


    $receipt_id_run = $fileName;

    $_source = rtrim($_SERVER['DOCUMENT_ROOT'], DIRECTORY_SEPARATOR).'/assets/document_bill/'.@$receipt_id_run.'.pdf';
    $_target = '/assets/document_bill/'.@$receipt_id_run.'.pdf';
    $receipt_id = @$receipt_id_run;

    $pdf->Output($_source,'F');

    $_source = rtrim($_SERVER['DOCUMENT_ROOT'], DIRECTORY_SEPARATOR).'/assets/document_bill/'.$receipt_id_run.'.pdf';
    $_target = rtrim($_SERVER['DOCUMENT_ROOT'], DIRECTORY_SEPARATOR).'/assets/images/templete_img/bill';
    echo $_source;
    echo $_target;
    $image = new Imagick($_source);

 
  
  
    $image->setResolution( 550, 550 );
    $image->readImage($_source);
    // $watermark = new Imagick();
// $watermark->readImage("https://system.policehospital-coop.com/APIs/bill/image/logo.png");

// // Retrieve size of the Images to verify how to print the watermark on the image
// $img_Width = $image->getImageWidth();
// $img_Height = $image->getImageHeight();
// $watermark_Width = $watermark->getImageWidth();
// $watermark_Height = $watermark->getImageHeight();

// // Check if the dimensions of the image are less than the dimensions of the watermark
// // In case it is, then proceed to 
// if ($img_Height < $watermark_Height || $img_Width < $watermark_Width) {
//     // Resize the watermark to be of the same size of the image
//     $watermark->scaleImage($img_Width, $img_Height);

//     // Update size of the watermark
//     $watermark_Width = $watermark->getImageWidth();
//     $watermark_Height = $watermark->getImageHeight();
// }

// // Calculate the position
// $x = ($img_Width - $watermark_Width) / 2;
// $y = ($img_Height - $watermark_Height) / 2;
// $watermark->setImageOpacity(0.7);
// // Draw the watermark on your image
// $image->compositeImage($watermark, Imagick::COMPOSITE_OVER, $x, $y);


// From now on depends on you what you want to do with the image
// for example save it in some directory etc.
// In this example we'll Send the img data to the browser as response
// with Plain PHP
// header("Content-Type: image/" . $image->getImageFormat());

    $num_pages = $image->getNumberImages();
    $image->setImageCompressionQuality(100);
    
    $image->setImageBackgroundColor('#ffffff');
  $image->setImageAlphaChannel(Imagick::ALPHACHANNEL_REMOVE);
    //$image = $image->mergeImageLayers(Imagick::LAYERMETHOD_FLATTEN);
  
    for ($i = 0; $i < $num_pages; $i++) {
        $image->setIteratorIndex($i);
        $image->setImageFormat('png');
        $image->writeImage($_target . '/' . $receipt_id_run.'.png');
    }

    $image->clear();
    $image->destroy();

     $path = $_target. '/' . $receipt_id_run .'_1.jpg';
     $type = pathinfo($path, PATHINFO_EXTENSION);
     $data = file_get_contents($path);
     $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

    // ob_start();
    header('content-type: application/json;charset=utf-8');
    header("Access-Control-Allow-Origin: *");
    header('Access-Control-Allow-Headers: X-Requested-With, content-type, access-control-allow-origin, access-control-allow-methods, access-control-allow-headers');
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
  }else{
    $pdf->Ln(5); 
   $pdf->MultiCell(100, 0, '<img src="image/ok3.png" style="width:30px;margin-left:150px;" />', 0, 'C', 0, 0, '', '', true, 0, true, true, 0);
            $txt_sh = 'การขอกู้เสร็จสมบูรณ์';
            
            $pdf->Ln(16); 
            $pdf->SetFillColor(255, 255, 255);
           // $pdf->SetFontSize(25);
            $pdf->SetFont('thsarabun','B',25);
            $pdf->SetTextColor(10, 10, 10);
            $pdf->Cell(0, 0, $txt_sh, 0, 1, 'C', 0, 'B', 0);
            $pdf->Ln(0);
            // Removes bold
            $pdf->SetFont('');
            $pdf->SetFillColor(255, 255, 255);
            $pdf->SetFontSize(16);
            $pdf->SetTextColor(126, 124, 124);
            $pdf->Cell(0, 0, 'ระบบได้ทำการโอนเงินไปยังบัญชีออมทรัพย์สินมัธยัสถ์', 0, 1, 'C', 0, '', 0);
            $pdf->Ln(0);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->SetFontSize(25);
            $pdf->SetTextColor(10, 10, 10);
            $pdf->Cell(0, 0, 'เลขที่ '.$account_id, 0, 1, 'C', 0, '', 0);
            $pdf->Ln(0);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->SetFontSize(25);
            $pdf->SetTextColor(10, 10, 10);
            $pdf->MultiCell(100, 0, 'จำนวน <span style="color:#1A864B"><b>'.$amount.'</b></span><span style="font-size:16px" > บาท</span>', 0, 'C', 0, 0, '', '', true, 0, true, true, 0);
            $pdf->Ln(9);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->SetFontSize(25);
            $pdf->SetTextColor(10, 10, 10);
            $pdf->Cell(0, 0, 'เรียบร้อยแล้ว', 0, 1, 'C', 0, '', 0);


    $receipt_id_run = $fileName;

    $_source = rtrim($_SERVER['DOCUMENT_ROOT'], DIRECTORY_SEPARATOR).'/assets/document_bill/'.@$receipt_id_run.'.pdf';
    $_target = '/assets/document_bill/'.@$receipt_id_run.'.pdf';
    $receipt_id = @$receipt_id_run;

    $pdf->Output($_source,'F');

    $_source = rtrim($_SERVER['DOCUMENT_ROOT'], DIRECTORY_SEPARATOR).'/assets/document_bill/'.$receipt_id_run.'.pdf';
    $_target = rtrim($_SERVER['DOCUMENT_ROOT'], DIRECTORY_SEPARATOR).'/assets/images/templete_img/bill';
    echo $_source;
    echo $_target;
    $image = new Imagick($_source);
    $image->setResolution( 550, 550 );
    $image->readImage($_source);
    $num_pages = $image->getNumberImages();
    $image->setImageCompressionQuality(100);
    
    $image->setImageBackgroundColor('#ffffff');
  $image->setImageAlphaChannel(Imagick::ALPHACHANNEL_REMOVE);
    //$image = $image->mergeImageLayers(Imagick::LAYERMETHOD_FLATTEN);
  
    for ($i = 0; $i < $num_pages; $i++) {
        $image->setIteratorIndex($i);
        $image->setImageFormat('png');
        $image->writeImage($_target . '/' . $receipt_id_run.'.png');
    }

    $image->clear();
    $image->destroy();

     $path = $_target. '/' . $receipt_id_run .'_1.jpg';
     $type = pathinfo($path, PATHINFO_EXTENSION);
     $data = file_get_contents($path);
     $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

    // ob_start();
    header('content-type: application/json;charset=utf-8');
    header("Access-Control-Allow-Origin: *");
    header('Access-Control-Allow-Headers: X-Requested-With, content-type, access-control-allow-origin, access-control-allow-methods, access-control-allow-headers');
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');

  }

     if($_GET['is_image'] == 'true'){

      $receipt_id_run = @$row_receipt['receipt_id'];

      $_source = rtrim($_SERVER['DOCUMENT_ROOT'], DIRECTORY_SEPARATOR).'/assets/document/'.@$receipt_id_run.'.pdf';
      $_target = '/assets/document/'.@$receipt_id_run.'.pdf';
      $receipt_id = @$receipt_id_run;

      $pdf->Output($_source,'F');
      header('Location: '.base_url('admin/pdf_to_image').'?_target='.$receipt_id);
      exit;
      

  }else if($_GET['is_base64'] == 'true'){
    // if($index != 4){
      $receipt_id_run = $fileName;

      $_source = rtrim($_SERVER['DOCUMENT_ROOT'], DIRECTORY_SEPARATOR).'/assets/document_bill/'.@$receipt_id_run.'.pdf';
      $_target = '/assets/document_bill/'.@$receipt_id_run.'.pdf';
      $receipt_id = @$receipt_id_run;

      $pdf->Output($_source,'F');

      $_source = rtrim($_SERVER['DOCUMENT_ROOT'], DIRECTORY_SEPARATOR).'/assets/document_bill/'.$receipt_id_run.'.pdf';
      $_target = rtrim($_SERVER['DOCUMENT_ROOT'], DIRECTORY_SEPARATOR).'/assets/images/templete_img/bill';
      echo $_source;
      echo $_target;
      $image = new Imagick($_source);

	 
	  
	  
      $image->setResolution( 550, 550 );
      $image->readImage($_source);
      $num_pages = $image->getNumberImages();
      $image->setImageCompressionQuality(100);
      
      $image->setImageBackgroundColor('#ffffff');
	  $image->setImageAlphaChannel(Imagick::ALPHACHANNEL_REMOVE);
      //$image = $image->mergeImageLayers(Imagick::LAYERMETHOD_FLATTEN);
    
      for ($i = 0; $i < $num_pages; $i++) {
          $image->setIteratorIndex($i);
          $image->setImageFormat('png');
          $image->writeImage($_target . '/' . $receipt_id_run.'.png');
      }

      $image->clear();
      $image->destroy();

       $path = $_target. '/' . $receipt_id_run .'_1.jpg';
       $type = pathinfo($path, PATHINFO_EXTENSION);
       $data = file_get_contents($path);
       $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

      // ob_start();
      header('content-type: application/json;charset=utf-8');
      header("Access-Control-Allow-Origin: *");
      header('Access-Control-Allow-Headers: X-Requested-With, content-type, access-control-allow-origin, access-control-allow-methods, access-control-allow-headers');
      header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
      // echo json_encode(array('data'=> $receipt_id));
      // exit;
    // }
    }
    
?>