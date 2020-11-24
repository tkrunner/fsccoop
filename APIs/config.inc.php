<?php 
    header("Content-Type:text/json;charset=utf-8");
	header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
    header('Access-Control-Allow-Headers: X-Requested-With, content-type, access-control-allow-origin, access-control-allow-methods, access-control-allow-headers');
    
    date_default_timezone_set('Asia/Bangkok');
    define("PATH", $_SERVER["DOCUMENT_ROOT"]);
    // define("ONLINE_URL", "https://www.nationco-op.org/");
    //define("SYSTEM_URL", "https://www.tistrsaving.com/");

    require_once(PATH."/APIs/inc/connect.inc.php");
    require_once(PATH."/APIs/inc/function.inc.php");

    $request = json_decode(file_get_contents("php://input"));
	if (!empty($request)){
		$_POST = array_merge($_POST, (array) json_decode(file_get_contents('php://input')));
    }
?>
