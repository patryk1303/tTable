<?php
define("DB_SERV", "localhost");
define("DB_USER", "root");
define("DB_PASS", "qwe");
define("DB_BASE", "parser_jazdynowy");

$prefix = "";
$timezone = 'Europe/Warsaw';
date_default_timezone_set($timezone);

$mysqli = new mysqli(DB_SERV,DB_USER,DB_PASS,DB_BASE);
$mysqli->query("SET character_set_results=utf8");
?>