<?php 
    require_once("config.inc.php");

    $ref_2 = isset($_POST['ref_2']) ? $mysqli->real_escape_string($_POST['ref_2']) : null;

    $sql = "SELECT *, SUM(amount) as total FROM coop_payin WHERE ref_2 = '".$ref_2."'";
    $rs = $mysqli->query($sql);
    echo $mysqli->error;
    $payin = $rs->fetch_assoc();

    $sql = "SELECT * FROM coop_payin_setting WHERE type = 'tax_id' AND status = 1";
    $rs = $mysqli->query($sql);
    echo $mysqli->error;
    $setting = $rs->fetch_assoc();
    $payin['tax_id'] = $setting['value'];

    $sql = "SELECT t2.prename_full, t1.firstname_th, t1.lastname_th FROM coop_mem_apply as t1
                LEFT JOIN coop_prename as t2 ON t1.prename_id = t2.prename_id
                WHERE member_id = '".$payin['member_id']."'";
    $rs = $mysqli->query($sql);
    echo $mysqli->error;
    $member = $rs->fetch_assoc();
    $payin['name'] = $member['prename_full'].$member['firstname_th']." ".$member['lastname_th'];

    echo json_encode($payin);
?>
