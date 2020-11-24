<?php
  require_once("tcpdf/tcpdf.php");

  $pdf = new TCPDF('P', 'mm',array(100, 130), true, 'UTF-8', false);
  // $pdf = new FPDF('L','mm','A5');	
//   $width = 175;  
// $height = 266; 
// $orientation = ($height>$width) ? 'P' : 'L';  
// $pdf->addFormat("custom", $width, $height);  
// $pdf->reFormat("custom", $orientation);  
  // set document information
  $pdf->SetCreator(PDF_CREATOR);
  $pdf->SetAuthor('สหกรณ์ออมทรัพย์โรงพยาบาลตำรวจ จำกัด ');
  $pdf->SetTitle('ใบเสร็จโอนเงิน');
  $pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);
  $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
  $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
  $pdf->SetDefaultMonospacedFont('thsarabun');
  // $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_RIGHT);
  $pdf->setPrintHeader(false);
  $pdf->setPrintFooter(false);
  $pdf->SetAutoPageBreak(TRUE, 10);
  $pdf->SetFont('thsarabun', '', 14, '', true);
  define('MYPDF_MARGIN_LEFT',0);
define('MYPDF_MARGIN_TOP',0);
define('MYPDF_MARGIN_RIGHT',0);
define('MYPDF_MARGIN_HEADER',0);
define('MYPDF_MARGIN_FOOTER',0);
// กำหนดขอบเขตความห่างจากขอบ  กำหนดเพิ่มเติมในไฟล์  tcpdf_config.php 
$pdf->SetMargins(MYPDF_MARGIN_LEFT, MYPDF_MARGIN_TOP, MYPDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(MYPDF_MARGIN_HEADER);
$pdf->SetFooterMargin(MYPDF_MARGIN_FOOTER);

  // add a page
  $pdf->AddPage();

  // set cell padding
  // $pdf->setCellPaddings(0, 0, 0, 0);

  // set cell margins
  // $pdf->setCellMargins(0, 0, 0, 0);

  // Header And Logo
  require_once("header_pdf.php");

  // $pdf->Ln(16);

  // Title
  // require_once("title_pdf.php");

  // Title Description
  // require_once("title_description_pdf.php");

  // Table
   require_once("table_pdf.php");

  // $pdf->Ln(4);
  $content = '';
  $content .= $tbl;
  $pdf->writeHTML($content, true, false, true, false, '');

  // Footer
  // require_once("footer_pdf.php");

  require_once("watermark_pdf.php");

  $pdf->lastPage();

  $pdf->Output('receipt.pdf', 'I');
?>
