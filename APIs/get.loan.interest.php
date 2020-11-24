<?php
    require_once("config.inc.php");
    require_once("parameter.inc.php");
    require_once("token.validate.php");

    $data = [ 'status' => 0, 'responseText' => '' ];

    $from_date = $_POST["from_date"];
    $thru_date = $_POST["thru_date"];
    $loan_amount = $_POST["loan_amount"];
    $loan_type = $_POST["loan_type"];
    $sql = "SELECT calc_loan_interest(".$loan_amount.", ".$loan_type.", '".$from_date."', '".$thru_date."')";
    $rs = $mysqli->query($sql);
    $tmp_interest = $rs->fetch_assoc();
    $key = array_keys($tmp_interest);
    $interest = $tmp_interest[$key[0]];

    $data["data"]["interest"] = round($interest);
    $data["responseText"] = "success";
    echo json_encode($data);
    exit();
?>