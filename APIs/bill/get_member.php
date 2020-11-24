<?php
  $isMember = false;
  $member_name = '';
  $position = '';
  $sql = "SELECT memname, membgroup_desc FROM cmp_imp_member WHERE trim(member_no) = '{$member_no}'";
  $rs = $mysqli->query($sql);
  if ( $rs->num_rows ) {
    $row = $rs->fetch_assoc();
    $isMember = true;
    $member_name = trim($row['memname']);
    $position = trim($row['membgroup_desc']);
  }
?>