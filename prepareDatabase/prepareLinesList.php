<?php

function prepareLinesList($path, $lines) {
	global $prefix;
	$sql = "INSERT INTO `$prefix"."linie` (`id`,`linia`,`data`) VALUES ";

	$i = 0;

	foreach ($lines as $line) {
		if (file_exists("$path/$line/data"))
			$date = file("$path/$line/data");
		else
			$date = array("");
		if ($i != count($lines) - 1)
			$sql .= "(NULL,'$line','$date[0]'),";
		else
			$sql .= "(NULL,'$line','$date[0]')";
		$i++;

	}

	$sql .= ";";

	return $sql;
}
?>
