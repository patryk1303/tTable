<?php

$pathToSchedule = dirname(__FILE__) . "/rozklad1";

function getLines() {
	global $mysqli,$prefix;
	$lines = array();
	$sql = "SELECT linia FROM $prefix"."linie AS linie";
	$result = $mysqli->query($sql);
	while($row = $result->fetch_assoc()) {
		$lines[] = $row['linia'];
	}
	return $lines;
}

function getDirections() {
	global $mysqli,$prefix;
	$dirs = array();
	$sql = "SELECT linia_id, nr_kier, kierunek FROM $prefix"."kierunki AS kierunki";
	$result = $mysqli->query($sql);
	while($row = $result->fetch_assoc()) {
		$dirs[] = array($row['linia_id'],$row['nr_kier'],$row['kierunek']);
	}
	return $dirs;
}

function getLineID($line) {
	global $mysqli,$prefix;
	$sql = "SELECT id FROM $prefix"."linie AS linie WHERE linia='$line'";
	$result = $mysqli->query($sql);
	while($row = $result->fetch_assoc()) {
		$lineID = $row['id'];
	}
	return $lineID;
}

function getDirectionID($line,$dirNumber) {
	global $mysqli,$prefix;
	$lineID = getLineID($line);
	$sql = "SELECT id FROM $prefix"."kierunki AS kierunki WHERE linia_id=$lineID AND nr_kier=$dirNumber";
	$mysqli -> set_charset("utf-8");
	$result = $mysqli->query($sql);
	while($row = $result->fetch_assoc()) {
		$dirID = $row['id'];
	}
	return $dirID;
}

function getStopID($fullName) {
	global $mysqli,$prefix;
	$fullName = trim($fullName);
	//$sql = "SELECT id FROM $prefix"."przystanki AS przystanki WHERE nazwa_pelna='$fullName'";
	$sql = "SELECT id FROM $prefix"."przystanki AS przystanki WHERE nazwa_pelna LIKE '%$fullName%'";
	$result = $mysqli->query($sql);
	while($row = $result->fetch_assoc()) {
		$stopID = $row['id'];
	}
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
	while($row = $result->fetch_assoc()) {
		$stopID = $row['id'];
	}
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