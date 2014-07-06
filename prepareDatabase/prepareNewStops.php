<?php
/*define('DB_HOST','localhost');
define('DB_USER','root');
define('DB_PASS','qwe');
define('DB_NAME','parser_jazdynowy');

$mysqli = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);*/
$mysqli->query("SET character_set_results=utf8");

//$prefix = "";

require_once 'utils.php';

echo prepareNewStops($pathToSchedule, $lines);

function prepareNewStops($path, $lines) {
	global $prefix;
	$currentStopsTemp = getStopsAndIDs();
	$currentStops = array();
	
	foreach($currentStopsTemp as $currentStop) {
		$currentStops[] = $currentStop[1];
	}
	
	$stops = array();
	$stops1 = array();

	foreach ($lines as $line) {
		$dirCount = count(file("$path/$line/kierunki"));
		for($i=1;$i<=$dirCount;$i++) 
			$stops[] = file("$path/$line/przystanki_$i");
	}

	foreach ($stops as $stop) {
		foreach ($stop as $s) {
			$stops1[] = trim($s);
		}
	}
	
	$stops1 = array_unique($stops1);
	sort($stops1);
	
	$newStops = array_diff($stops1, $currentStops);
	sort($newStops);
	
	$sql = "INSERT INTO $prefix"."przystanki('id','przystanek') VALUES ";
	
	$i = 0;
	foreach ($newStops as $stop) {
		$stop = trim($stop);
		$stop1 = explode("/", $stop, 2);
		if (count($stop1) == 1) {
			if (strpos($stop1[0],'nż') !== false) {
				$nz=1;
			} else {
				$nz=0;
			}
			//$stop[0] = trim($stop[0]);
			if ($i != count($stops1) - 1)
				$sql .= "(NULL,'$stop','$stop1[0]','','',$nz),";
			else
				$sql .= "(NULL,'$stop','$stop1[0]','','',$nz)";
		} else {
			//echo $stop[0]."--".$stop[1],"<br>";
			$stop1[0] = trim($stop1[0]);
			$stop1[1] = trim($stop1[1]);
			if (strpos($stop1[1],'nż') !== false) {
				$nz=1;
				$stop1[1] = str_replace(" – nż","",$stop1[1]);
				$stop1[1] = str_replace(" - nż","",$stop1[1]);
			} else {
				$nz=0;
			}
			if ($i != count($stops1) - 1)
				$sql .= "(NULL,'$stop','$stop1[0]','$stop1[1]','',$nz),";
			else
				$sql .= "(NULL,'$stop','$stop1[0]','$stop1[1]','',$nz)";
		}
		$i++;
	}

	$sql .= ";";

	return $sql;
}

?>