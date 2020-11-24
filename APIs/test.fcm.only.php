<?php
    require_once("config.inc.php");

    $data = [ 'status' => 0 ];

    $response = firebaseCloudMessage("dZI1k162Zlo:APA91bEwgek0c_jAuNYNNFcay5gGnyPHvnLsdDjZ44WyECF7GWcp77XJV2D7M94SuSj5ZK2vsUeJZ5TKdYhJDTIRXc1yhF4gOFIJ6T71CPEyAVxZ0EjsrKNC1HTCPr33dcYCPOcc2qC-", "test_title", "test_message", 9, 40);
    $data['status'] = 1;
    $data['response'] = $response;

    echo json_encode($data); 
    exit();
?>
