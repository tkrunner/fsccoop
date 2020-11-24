<?php
  $isReceipt = false;
  $detail = '';
  $date = array();
  $period = array();
  $principal_payment = array();
  $interest_payment = array();
  $item_payment = array();
  $itembal = array();
  $total = 0.00;
  $thai_text = '';
  $sql = "SELECT keepdesc, operdtm, period, principal_payment, interest_payment, item_payment, itembal FROM cmp_imp_keep WHERE trim(receipt_no) = '{$receipt_no}'";
  $rs = $mysqli->query($sql);
  if ( $rs->num_rows ) {
    $isReceipt = true;
    $detail = trim($row['keepdesc']);
    while( ($row = $rs->fetch_assoc()) ){
      $date = date2thaiformat($row['operdtm']);
      $detail[] = trim($row['keepdesc']);
      $period[] = $row['period'];
      $principal_payment[] = number_format($row['principal_payment'], 2);
      $interest_payment[] = number_format($row['interest_payment'], 2);
      $item_payment[] = number_format($row['item_payment'], 2);
      $itembal[] = number_format($row['itembal'], 2);
      $total += $row['item_payment'];
      $thai_text += $row['item_payment'];
    }
    $thai_text = NumberToChar($total);
    $total = number_format($total, 2);
  }
?>