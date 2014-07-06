<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'qwe');
define('DB_NAME', 'parser_jazdynowy');

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

require_once 'prepareStopListSQL.php';
require_once 'prepareLinesList.php';
require_once 'prepareDirectionsList.php';
require_once 'prepareDayTypes.php';
require_once 'utils.php';

$stops = prepareStopListSQL($pathToSchedule, $lines);
//echo $stops;

//echo "<br>";
//echo getStopID("Łukasiewicza / NordGlass");
?>
