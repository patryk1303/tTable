<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'qwe');
define('DB_NAME', 'parser_jazdynowy');

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
$mysqli->query("SET character_set_results=utf8");
?>
<!doctype html>
<html>
	<head>
		<title>Przygotuj odjazdy</title>
		<meta charset="UTF-8">
	</head>
	<body>
		<?php
		require_once 'prepareDepartures_old.php';
		require_once 'utils.php';

		echo "Odjazdy:<br>\n" . prepareDepatrues($pathToSchedule, $lines) . "<br><hr><br>\n";
		?>
	</body>
</html>