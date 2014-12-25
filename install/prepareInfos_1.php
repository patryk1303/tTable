<?php

require_once 'utils.php';

function prepareInfos_1($path, $lines) {
	global $mysqli,$prefix;
	
	$sql = "INSERT INTO `$prefix"."oznaczenia_1` (`id`,`linia_id`,`kier_id`,`tekst`,`oznaczenie`,`opis`) VALUES ";
	
	foreach($lines as $line) {
		$directionsNum = count(file("$path/$line/kierunki"));
		$lineID = getLineID($line);
		for($i=1;$i<=$directionsNum;$i++) {
			$dirID = getDirectionID($line, $i);
			$infos = file("$path/$line/legenda_$i");
			foreach($infos as $info) {
				$i1 = explode(" - ",$info);
				/*echo "$line - $i<br>";
				print_r($i1);
				echo "<br>";*/
				$sql .= "(NULL,$lineID,$dirID,'$info','$i1[0]','$i1[1]'),";
			}
		}
	}
	
	$sql .= ";";

	$sql = str_replace(",;", ";", $sql);
	//echo $sql;
	return ($sql);
}
?>
	