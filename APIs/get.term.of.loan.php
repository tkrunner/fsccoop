<?php
    require_once("config.inc.php");
    require_once("parameter.inc.php");
    require_once("token.validate.php");

    $data = [ 'status' => 0, 'responseText' => '' ];

    //Get loan term
    $sql = "SELECT * FROM coop_term_of_loan WHERE type_id =  ".$_POST["type_id"]." AND start_date <= CURDATE() ORDER BY start_date DESC";
    $rs = $mysqli->query($sql);
    $term = $rs->fetch_assoc();

    //Generate Result
    $result = array();
    $result["term"] = $term;

    $data['responseText'] = "success";
    $data["data"] = $result;
    echo json_encode($data);
    exit();
?>