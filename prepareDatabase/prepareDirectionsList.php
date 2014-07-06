<?php
require_once 'utils.php';

function prepareDirectionsList($path, $lines) {
	global $mysqli,$prefix;
	$sql = "INSERT INTO `$prefix"."kierunki` (`id`,`linia_id`,`nr_kier`,`kierunek`) VALUES ";

	$i = 0;

	foreach ($lines as $line) {
		$lineID = getLineID($line);
		if (file_exists("$path/$line/kierunki"))
			$dirs = file("$path/$line/kierunki");
		else
			return null;

		$j = 1;
		foreach ($dirs as $dir) {
			$dir = trim($dir);
			if ($i != count($lines))
				$sql .= "(NULL,$lineID,$j,'$dir'),";
			else
				$sql .= "(NULL,$lineID,$j,'$dir')";
			$j++;
		}
		$i++;
	}

	$sql .= ";";

	$sql = str_replace(",;", ";", $sql);
	return $sql;
}
?>