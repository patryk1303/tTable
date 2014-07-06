<?php

$pathToSchedule = dirname(__FILE__) . "/rozklad1";
$lines = array(2, 3, 4, 6, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17);

$prefix = "";

function getLineID($line) {
	global $mysqli,$prefix;
	$sql = "SELECT id FROM $prefix"."linie AS linie WHERE linia='$line'";
	$result = $mysqli->query($sql);
	$lineID = $result->fetch_assoc()['id'];
	return $lineID;
}

function getDirectionID($line,$dirNumber) {
	global $mysqli,$prefix;
	$lineID = getLineID($line);
	$sql = "SELECT id FROM $prefix"."kierunki AS kierunki WHERE linia_id=$lineID AND nr_kier=$dirNumber";
	$mysqli -> set_charset("utf-8");
	$result = $mysqli->query($sql);
	$dirID = $result->fetch_assoc()['id'];
	return $dirID;
}

function getStopID($fullName) {
	global $mysqli,$prefix;
	$fullName = trim($fullName);
	//$sql = "SELECT id FROM $prefix"."przystanki AS przystanki WHERE nazwa_pelna='$fullName'";
	$sql = "SELECT id FROM $prefix"."przystanki AS przystanki WHERE nazwa_pelna LIKE '%$fullName%'";
	$result = $mysqli->query($sql);
	$stopID = $result->fetch_assoc()['id'];
	return $stopID;
}

function getStopID1($name1,$name2) {
	global $mysqli,$prefix;
	$name1 = trim($name1);
	$name2 = trim($name2);
	//$sql = "SELECT id FROM $prefix"."przystanki AS przystanki WHERE nazwa_pelna='$fullName'";
	$sql = "SELECT id FROM $prefix"."przystanki AS przystanki WHERE nazwa1 LIKE '%$name1%' AND nazwa2 LIKE '%$name2%'";
	//echo $sql."<br>";
	$result = $mysqli->query($sql);
	$stopID = $result->fetch_assoc()['id'];
	return $stopID;
}

function getStopsAndIDs() {
	global $mysqli,$prefix;
	$stops = array();
	$sql = "SELECT id,nazwa_pelna,nazwa1,nazwa2 FROM $prefix"."przystanki AS przystanki";
	//echo $sql;
	$result = $mysqli->query($sql);
	while($row = $result->fetch_assoc()) {
		$stops[] = array($row['id'],$row['nazwa_pelna'],$row['nazwa1'],$row['nazwa2']);
	}
	return $stops;
}

function getDayTypes() {
	global $mysqli,$prefix;
	$dT = array();
	$sql = "SELECT * FROM $prefix"."typy_dni AS typy_dni";
	$result = $mysqli->query($sql);
	while($row = $result->fetch_assoc()) {
		$dT[] = array($row['id'],$row['nazwa']);
	}
	return $dT;
}
?>