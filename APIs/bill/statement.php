<?php
    require_once("connect.inc.php");

    $member_no = $_GET['member_no'];
    $yearto = $_GET['yearto'];
    $year = $_GET['year'];
    if($member_no) {
      require_once("get_member.php");
      require_once("get_receipt.php");
      if ($isMember AND $isReceipt) {
        require_once("setting_pdf.php");
      } else {
        header("Location: ".BASE_URL."/share/pagenotfound".$member_no);
        exit();
      }
    } else {
      header("Location: ".BASE_URL."/share/pagenotfound".$member_no);
      exit();
    }

?>