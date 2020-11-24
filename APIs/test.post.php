<?php
    header("Content-Type:text/json;charset=utf-8");
	header("Access-Control-Allow-Origin: *");
    header('Access-Control-Allow-Headers: X-Requested-With, content-type, access-control-allow-origin, access-control-allow-methods, access-control-allow-headers');
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    
    $data = [ 'status' => 0, 'responseText' => http_response_code() ];
    echo json_encode($data); 
    exit();
?>