<?php
//BARDZO WAŻNE! PLIKI Z ROZKŁADAMI MAJĄ BYĆ ZAPISANE BEZ DOM!!

//update odjazdy set godz = replace(godz, 'ï»¿', '');
//update oznaczenia set `oznaczenia` = replace(`oznaczenia`, '﻿', '');

$save=false;

require_once 'utils.php';
$lines = array(13);

function prepareDepatrues($path, $lines) {
	global $mysqli,$prefix,$save;
	$dayTypes = getDayTypes();
	$stopsList = getStopsAndIDs();
	//print_r($stopsList);
	$regex = "/([0-9][0-9])([a-zA-Z]*)/";
	$polskie_literki = array("ą","ę","ł","ó","ś","ć","ź","ż","ń","Ą","Ę","Ł","Ó","Ś","Ć","Ź","Ż","Ń");
	//print_r($stopsList);
	//print_r($dayTypes);

	//$sql = "INSERT INTO `odjazdy` (`id`,`typ_dnia_id`,`linia_id`,`przyst_id`,`kier_id`,`kurs_nr`,`odjazd`,`godz`,`min`,`oznaczenia`) VALUES ";

	foreach ($lines as $line) {
		$sql = "INSERT INTO `$prefix"."odjazdy` (`id`,`typ_dnia_id`,`linia_id`,`przyst_id`,`kier_id`,`kurs_nr`,`odjazd`,`godz`,`min`,`oznaczenia`,`stan`) VALUES ";

		$directions = file("$path/$line/kierunki");
		$lineID = getLineID($line);

		for ($kierunek = 1; $kierunek <= count($directions); $kierunek++) {
			$kierunekID = getDirectionID($line, $kierunek);
			//echo $kierunekID."<br>";
			$przystanki = file("$path/$line/przystanki_$kierunek");
			$nrPrzystanek = 0;
			foreach ($przystanki as $przystanek) {	//pobranie ID przystanku - metoda 1, poprzez listę asocjacyjną
				//echo $przystanek;
				$stopID = NULL;
				$przystanek = trim($przystanek);
				foreach ($stopsList as $stopFromList) {
					$stopFromList[1] = trim($stopFromList[1]);
					//echo "'$stopFromList[1]' == '$przystanek'? ";
					//if ($stopFromList[1] == $przystanki[$nrPrzystanek]) {
					if ($stopFromList[1] == $przystanek) {
						//echo "ok<br><hr>";
						$stopID = $stopFromList[0];
						break;
					}
					//echo "<br>";
				}
				$k = 1;
				$przy = explode("/",$przystanek);
				$przy = array_map("trim",$przy);
				$przy[0] = str_replace(" – nż","",$przy[0]);
				$przy[0] = str_replace(" – nż","",$przy[0]);
				$przy[0] = str_replace($polskie_literki,"%",$przy[0]);
				//pobieranie Id przystanku - metoda 2, baza danych (gdy metoda 1 zawiedzie)
				if(count($przy)==2) {
					$przy[1] = str_replace(" - nż","",$przy[1]);
					$przy[1] = str_replace(" – nż","",$przy[1]);
					$przy[1] = str_replace($polskie_literki,"%",$przy[1]);
					if(!isset($stopID))
						$stopID = getStopID1($przy[0], $przy[1]);
				} else {
					if(!isset($stopID))
						$stopID = getStopID1($przy[0],"");
				}
				//print_r($przy);
				//echo "$przystanek: $stopID<br>";
				foreach ($dayTypes as $tD) {
					$odjazdy = file("$path/$line/$kierunek" . "_" . $k);
					$odjazdy1 = array();
					//print_r($)
					foreach ($odjazdy as $odjazd) {
						$odjazdy1[] = explode("\t", $odjazd);
					}
					$ileOdjazdow = count($odjazdy1[0]);
					
					/*echo "<table border=\"1\" style=\"margin: 10px;\">";
					foreach($odjazdy1 as $o1) {
						echo "<tr>";
						foreach($o1 as $o)
							echo "<td>$o</td>";
						echo "</tr>";
					}
					echo "</table>";*/
					
					//tutaj magia!
					//TODO zmiana z łażenia po przystanach na łażenie po kursach!
					if (isset($stopID)) {
						$nrKurs=0;
						//$odjTemp = array();
						foreach ($odjazdy1[$nrPrzystanek] as $odjazd) {
							$odjazd = trim($odjazd);
							if (strlen($odjazd) >= 4) {
								$odj = explode(":",$odjazd);
								preg_match($regex, $odj[1], $min);
								//$odjTemp[] = array($odjazd,$odj[0],$min[1],$min[2]);
								$sql .= "(NULL,$tD[0],$lineID,$stopID,$kierunekID,$nrKurs,'$odjazd','$odj[0]','$min[1]','$min[2]',0),";
							}
							$nrKurs++;
						}
						/*$odjNr = 0;
						foreach($odjTemp as $odjT) {
							$stan = ($odjNr == count($odjTemp)-1);
							if ($stan) 
								$sql .= "(NULL,$tD[0],$lineID,$stopID,$kierunekID,$nrKurs,'$odjT[0]','$odjT[1]','$odjT[2]','$odjT[3]',1),";
							else
								$sql .= "(NULL,$tD[0],$lineID,$stopID,$kierunekID,$nrKurs,'$odjT[0]','$odjT[1]','$odjT[2]','$odjT[3]',0),";

							$nrKurs++;
							$odjNr++;
						}*/
					} else {
						echo "Nie pobrano ID przystanku $przystanek, linia $line, kierunek ".$directions[($kierunek-1)]."<br>";
					}
					$k++;
				}
				$nrPrzystanek++;
			}
		}
		$sql .= ";";
		$sql = str_replace(",;", ";", $sql);
		if($save) {
			$mysqli->query($sql);
		}
		file_put_contents("$path/odjazdy_old$line.sql", $sql);
		//}
	}
	//$mysqli->query("update $prefix"."odjazdy set godz = replace(godz, 'ï»¿', '');")
}
?>