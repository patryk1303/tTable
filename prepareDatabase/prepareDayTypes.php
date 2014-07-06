<?php

function prepareDayTypes($path) {
	global $prefix;
	if (file_exists("$path/typy_dni"))
		$types = file("$path/typy_dni");
	else
		return array("");

	$sql = "INSERT INTO `$prefix"."typy_dni` (`id`,`nazwa`) VALUES ";

	$i = 0;

	foreach ($types as $type) {
		$type = trim($type);
		$sql .= "(NULL,'$type'),";
	}

	$sql .= ";";
	$sql = str_replace(",;", ";", $sql);
	return $sql;
}
?>