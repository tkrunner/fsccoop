<?php 
    require_once("config.inc.php");
    require_once("token.validate.php");
    //require_once("token.system.validate.php");//For test on dev.
    $suffix = isset($_POST['suffix']) ? $mysqli->real_escape_string($_POST['suffix']) : null;
    $type = isset($_POST['type']) ? $mysqli->real_escape_string($_POST['type']) : null;
    
    $sql = "SELECT * FROM coop_payin WHERE type = '".$type."' ORDER BY ref_2 DESC";
    
    $rs = $mysqli->query($sql);
    echo $mysqli->error;
    $payin = $rs->fetch_assoc();
    if (!empty($payin)) {
        $ref_2 = $payin['ref_2'] + 1;
    } else {
        $ref_2 = ($type+1)."00000001";
    }

    $ref_2 =  $mysqli->real_escape_string($ref_2); 

    if($type == 0) {
        $amount = isset($_POST['amount']) ? $mysqli->real_escape_string(str_replace( ',', '',$_POST['amount'])) : null;
        $sql = "INSERT INTO coop_payin (type, ref_2, member_id, amount, loancontract_no, deptaccount_no, paid, created_at, updated_at) 
                    VALUES ('{$type}', '{$ref_2}', '{$member_id}', '{$amount}', null, null, 0, NOW(), NOW())";
        $mysqli->query($sql);
        echo $mysqli->error;
    }  else if ($type == 1) {
        $deptaccount_nos = $_POST['deptaccount_no'];
        foreach($deptaccount_nos as $no) {
            $amount = isset($_POST[$no."_amount"]) ? $mysqli->real_escape_string(str_replace( ',', '', $_POST[$no."_amount"])) : null;

            $sql = "INSERT INTO coop_payin (type, ref_2, member_id, amount, loancontract_no, deptaccount_no, paid, created_at, updated_at) 
                        VALUES ('{$type}', '{$ref_2}', '{$member_id}', '{$amount}', '{$no}', null, 0, NOW(), NOW())";
            $mysqli->query($sql);
            echo $mysqli->error;
        }
    } else if ($type == 2) {
        $deptaccount_nos = $_POST['deptaccount_no'];
        foreach($deptaccount_nos as $no) {
            $amount = isset($_POST[$no."_amount"]) ? $mysqli->real_escape_string(str_replace( ',', '', $_POST[$no."_amount"])) : null;

            $sql = "INSERT INTO coop_payin (type, ref_2, member_id, amount, loancontract_no, deptaccount_no, paid, created_at, updated_at) 
                        VALUES ('{$type}', '{$ref_2}', '{$member_id}', '{$amount}', null, '{$no}', 0, NOW(), NOW())";
            $mysqli->query($sql);
            echo $mysqli->error;
        }
    }

    echo $ref_2;
?>