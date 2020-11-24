<?php

    header("Content-Type:text/json;charset=utf-8");
	header("Access-Control-Allow-Origin: *");
    header('Access-Control-Allow-Headers: X-Requested-With, content-type, access-control-allow-origin, access-control-allow-methods, access-control-allow-headers');
    
    $data['status'] = 0;
    $data['error_code'] = 101;   

    echo json_encode($data);
    exit();
?>