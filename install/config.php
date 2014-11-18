<?php
	define('DB_HOST','localhost');
	define('DB_USER','root');
	define('DB_PASS','qwe');
	define('DB_NAME','parser_jazdynowy');
	
	$prefix = "";
	
	$mysqli = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
	//$mysqli->query("SET character_set_results=utf8");
	$mysqli->set_charset("utf8");
	
	$lines = array(2, 3, 4, 6, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17,18);
	
?>