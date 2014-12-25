<?php
define("DB_SERV", "");
define("DB_USER", "");
define("DB_PASS", "");
define("DB_BASE", "");

$lang = "en";
$prefix = "";
$timezone = 'Europe/Warsaw';
date_default_timezone_set($timezone);

$mysqli = new mysqli(DB_SERV,DB_USER,DB_PASS,DB_BASE);
$mysqli->query("SET character_set_results=utf8");
?>