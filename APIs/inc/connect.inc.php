<?php

	/*
	define("HOSTNAME","fsccoop.upbean.co.th");
	define("DBNAME","upfsccoop_sys");
	define("USERNAME","upfsccoop_sys");
	define("PASSWORD","xohnUgsZe");
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: GET POST OPTIONS PUT DELETE");
    header("Content-Type:text/json;charset=utf-8");
    header("Access-Control-Allow-Headers: *");

	$mysqli = new mysqli( HOSTNAME , USERNAME , PASSWORD );
	$mysqli->select_db(DBNAME);
	$mysqli->query("SET NAMES utf8");
	*/
	
	define("HOSTNAME","localhost");
	define("DBNAME","bbcooporth_sys");
	define("USERNAME","bbcooporth_sys");
	define("PASSWORD","aUqNI14j");
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: GET POST OPTIONS PUT DELETE");
    header("Content-Type:text/json;charset=utf-8");
    header("Access-Control-Allow-Headers: *");

	$mysqli = new mysqli( HOSTNAME , USERNAME , PASSWORD );
	$mysqli->select_db(DBNAME);
	$mysqli->query("SET NAMES utf8");
	//connect db online
	$mysqli_app = new mysqli( "bbcoop.or.th" , "upcoopbb_web" , "D29aN2ZfNHT8TDzv" );
    $mysqli_app->select_db("upcoopbb_web");
    $mysqli_app->query("SET NAMES utf8");
	