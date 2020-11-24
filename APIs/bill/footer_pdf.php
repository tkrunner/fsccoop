<?php
  $pdf->SetAutoPageBreak(TRUE, 10);
  $first_column = '
    <div style="text-align:center;">
      <img src="image/teerapong_d.jpg" style="width:40px;" />
      <div style="border-bottom: 0.5px solid #00A4E5;"></div>
      <span>ผู้จัดการ</span>
    </div>
  ';
  $pdf->SetFillColor(255, 255, 255);
  $pdf->SetFontSize(16);
  $pdf->SetTextColor(0, 164, 229);
  $pdf->setCellPaddings(20, 10);
  $pdf->MultiCell(90, 0, $first_column, 0, 'J', 0, 0, '', '', true, 0, true, true, 0);

  $second_column = '
    
    <div style="text-align:center;">
    <img src="image/cashier.png" style="width:40px;" />
    <div style="border-bottom: 0.5px solid #00A4E5;"></div>
      <span>เจ้าหน้าที่การเงิน</span>
    </div>
  ';
  $pdf->SetFillColor(255, 255, 255);
  $pdf->SetFontSize(16);
  $pdf->SetTextColor(0, 164, 229);
  $pdf->MultiCell(90, 0, $second_column, 0, 'J', 0, 0, '', '', true, 0, true, true, 0);
?>
