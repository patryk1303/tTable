<?php
//echo $pathToSchedule;

function prepareStopListSQL($path, $lines) {
	global $prefix;
	$stops = array();
	$stops1 = array();

	foreach ($lines as $line) {
		$stops[] = file("$path/$line/przystanki_1");
	}

	foreach ($stops as $stop) {
		foreach ($stop as $s) {
			$stops1[] = $s;
		}
	}

	$stops1 = array_unique($stops1);
	sort($stops1);

	$sql = "INSERT INTO $prefix"."przystanki('id','przystanek') VALUES ";

	$i = 0;

	//print_r($stops1);

	foreach ($stops1 as $stop) {
		$stop = trim($stop);
		if ($i != count($stops1) - 1)
			$sql .= "(NULL,'$stop'),";
		else
			$sql .= "(NULL,'$stop')";
		$i++;
	}

	$sql .= ";";

	return $sql;
}
?>