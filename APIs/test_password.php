<?php 
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
  	require_once("config.inc.php");
	
	$password = "12345678" ; 
	$encry_password = encrypt_text($password) ;	
	echo $encry_password ; 	
