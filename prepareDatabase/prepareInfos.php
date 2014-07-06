<?php

require_once 'utils.php';

function prepareInfos($path, $lines) {
	global $mysqli,$prefix;
	
	$sql = "INSERT INTO `$prefix"."oznaczenia` (`id`,`linia_id`,`kier_id`,`oznaczenia`) VALUES ";
	
	foreach($lines as $line) {
		$directionsNum = count(file("$path/$line/kierunki"));
		$lineID = getLineID($line);
		for($i=1;$i<=$directionsNum;$i++) {
			$dirID = getDirectionID($line, $i);
			$infos = file("$path/$line/legenda_$i");
			$html = "";
			foreach($infos as $info) {
				$html .= "$info<br>";
			}
			$sql .= "(NULL,$lineID,$dirID,'$html'),";
		}
	}
	
	$sql .= ";";

	$sql = str_replace(",;", ";", $sql);
	return htmlspecialchars($sql);
}		
?>
	