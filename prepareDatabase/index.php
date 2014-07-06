<?php
	define('DB_HOST','localhost');
	define('DB_USER','root');
	define('DB_PASS','qwe');
	define('DB_NAME','parser_jazdynowy');
	
	$mysqli = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
?>
<!doctype html>
<html>
	<head>
		<title>Przygotuj odjazdy</title>
		<meta charset="UTF-8">
	</head>
	<body>
		<?php
		require_once 'prepareStopListSQL.php';
		require_once 'prepareLinesList.php';
		require_once 'prepareDirectionsList.php';
		require_once 'prepareDayTypes.php';
		require_once 'prepareInfos.php';
		require_once 'prepareNewStops.php';
		require_once 'utils.php';
	
		echo "Typy dni:<br>\n".prepareDayTypes($pathToSchedule)."<br><hr><br>\n";
		echo "Linie:<br>\n".prepareLinesList($pathToSchedule, $lines)."<br><hr><br>\n";
		echo "Kierunki:<br>\n".prepareDirectionsList($pathToSchedule, $lines)."<br><hr><br>\n\n";
		echo "Przystanki:<br>\n".prepareStopListSQL($pathToSchedule, $lines)."<br><hr><br>\n";
		echo "Oznaczenia:<br>\n".prepareInfos($pathToSchedule, $lines)."<br><hr>\n";
		echo "Nowe przystanki:<br>\n".prepareNewStops($pathToSchedule, $lines)."<br><hr>\n";
		echo "<a href=\"odjazdy.php\">Odjazdy tutaj</a>";
	?>
	</body>
</html>