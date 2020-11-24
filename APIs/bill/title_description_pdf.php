<?php
  $first_column = '
    <div>
      <span>เลขที่ใบเสร็จ</span><br>
      <span>ได้รับเงินจาก</span><br>
      <span>หน่วยงาน</span>
    </div>
  ';
  $pdf->SetFillColor(255, 255, 255);
  $pdf->SetFontSize(16);
  $pdf->SetTextColor(0, 164, 229);
  $pdf->MultiCell(25, 0, $first_column, 0, 'L', 0, 0, '', '', true, 0, true, true, 0);

  // $second_column = '
  //   <div>
  //     <span>'.$receipt_no.'</span><br>
  //     <span>'.$member_name.'</span><br>
  //     <span>'.$position.'</span>
  //   </div>
  // ';
  $second_column = '
  <div>
    <span>999999</span><br>
    <span>กันนิกา มานิดา</span><br>
    <span>ตำรวจ</span>
  </div>
';
  $pdf->SetFillColor(255, 255, 255);
  $pdf->SetFontSize(16);
  $pdf->SetTextColor(10, 10, 10);
  $pdf->MultiCell(95, 0, $second_column, 0, 'J', 1, 0, '', '', true, 0, true, true, 0);

  $third_column = '
    <div>
      <span>วันที่</span><br>
      <span>รหัสสมาชิก</span>
    </div>
  ';
  $pdf->SetFillColor(255, 255, 255);
  $pdf->SetFontSize(16);
  $pdf->SetTextColor(0, 164, 229);
  $pdf->MultiCell(25, 0, $third_column, 0, 'J', 1, 0, '', '', true, 0, true, true, 0);

  // $fourth_column = '
  //   <div>
  //     <span>'.$date.'</span><br>
  //     <span>'.$member_no.'</span>
  //   </div>
  // ';
  $fourth_column = '
    <div>
      <span>30 กรกฎาคม 2563</span><br>
      <span>999999</span>
    </div>
  ';
  $pdf->SetFillColor(255, 255, 255);
  $pdf->SetFontSize(16);
  $pdf->SetTextColor(10, 10, 10);
  $pdf->MultiCell(25, 0, $fourth_column, 0, 'J', 1, 0, '', '', true, 0, true, true, 0);

?>