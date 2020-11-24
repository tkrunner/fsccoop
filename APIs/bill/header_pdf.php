<?php
  // $header_left_column = '<img src="image/logo.png" style="width:30px;"/>';
  // $pdf->SetFillColor(212, 247, 228);
  // $pdf->MultiCell(9, 10, $header_left_column, 0, 'J', 1, 0, '', '', true, 0, true, true, 0);
  

  // $header_right_column = '
  //     <div style="position:absolute;right:0;">
  //         <span style="font-size:1em;">สหกรณ์ออมทรัพย์ธุรกิจก๊าซ ปตท. จำกัด</span><br>
  //     </div>
  // ';
  // $pdf->SetFillColor(212, 247, 228);
  // // $pdf->SetTextColor(26, 134, 75);
  // $pdf->SetTextColor(212, 247, 228);
  // $pdf->MultiCell(0, 0, $header_right_column, 0, 'J', 1, 0, '', '', true, 0, true, true, 0);
  // $pdf->Text(0, 1, "Product ID: 6666");
  // $pdf->SetFillColor(212, 247, 228);
  $header_left_column = '';
  $pdf->SetFillColor(212, 247, 228);
  $pdf->MultiCell(9, 12.3, $header_left_column, 0, 'J', 1, 0, '', '', true, 0, true, true, 0);
  $pdf->Image('image/logo.png', 15, 1, 10, 9, 'PNG');

  $header_right_column = '
      <div style="position:absolute;right:0;">
          <span style="font-size:1em;"></span><br>
      </div>
  ';
  $pdf->SetFillColor(212, 247, 228);
   $pdf->SetTextColor(26, 134, 75);
 // $pdf->SetTextColor(212, 247, 228);
  $pdf->MultiCell(0, 0, $header_right_column, 0, 'J', 1, 0, '', '', true, 0, true, true, 0);
  $pdf->Text(25, 3, "สหกรณ์ออมทรัพย์โรงพยาบาลตำรวจ จำกัด ");
  $pdf->SetFillColor(212, 247, 228);
  $pdf->writeHTML($header_right_column, true, false, true, false, '');
?>
