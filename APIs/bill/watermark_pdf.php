<?php
  $pdf->SetAutoPageBreak(TRUE, 10);
  $logo = '<div><img src="image/logo.png" style="width:250px;opacity:0.5;" /></div>';
  $pdf->SetFillColor(255, 255, 255);
  $pdf->SetXY(3, 40);
  $pdf->SetAlpha(0.2);
  $pdf->MultiCell(180, 0, $logo, 0, 'J', 1, 0, '', '', true, 0, true, true, 0);
?>