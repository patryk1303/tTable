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
			$k = 1;
			foreach ($dayTypes as $tD) {
			
				$odjazdy = file("$path/$line/$kierunek" . "_" . $k);
				$odjazdy1 = array();
				foreach ($odjazdy as $odjazd) {
					$odjazdy1[] = explode("\t", $odjazd);
				}
				$ileOdjazdow = count($odjazdy1[0]);
				
				$odjazdy2 = transpose($odjazdy1);
				
				$przystanki = file("$path/$line/przystanki_$kierunek");
				$nrKurs=0;
				foreach ($odjazdy2 as $odjazd) {
					$nrPrzystanek = 0;
					foreach ($przystanki as $przystanek) {
						//pobranie ID przystanku
						$stopID = NULL;
						$przystanek = trim($przystanek);
						foreach ($stopsList as $stopFromList) { //pobranie ID przystanku - metoda 1, poprzez listę asocjacyjną
							$stopFromList[1] = trim($stopFromList[1]);
							if ($stopFromList[1] == $przystanek) {
								$stopID = $stopFromList[0];
								break;
							}
						}
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
						//po pobraniu Id przystanku...tyle roboty dla takieog czegoś...
						
						//tutaj magia!
						//TODO zmiana z łażenia po przystanach na łażenie po kursach!
						if (isset($stopID)) {
							$odjTemp = array();
								
							$odj1 = $odjazd[$nrPrzystanek];
							$odj1D = strlen($odjazd[$nrPrzystanek]);

							if($odj1D > 2) {
								$odj = explode(":",$odj1);
								preg_match($regex, $odj[1], $min);
								
								$odj1 = str_replace("\n","",$odj1);
								
								$odjTemp[] = array($nrKurs,$odj1,$odj[0],$min[1],$min[2]);
							}
							
							//print_r($odjTemp);
							
							$odjTempIl = count($odjTemp)-1;
							$te = 0;
							foreach($odjTemp as $t) {
								//echo "$te - $odjTempIl<br>";
								//`odjazdy` (`id`,`typ_dnia_id`,`linia_id`,`przyst_id`,`kier_id`,`kurs_nr`,`odjazd`,`godz`,`min`,`oznaczenia`)
								if($te==$odjTempIl)
									$sql .= "(NULL,$tD[0],$lineID,$stopID,$kierunekID,$t[0],'$t[1]','$t[2]','$t[3]','$t[4]',1),";
								else
									$sql .= "(NULL,$tD[0],$lineID,$stopID,$kierunekID,$t[0],'$t[1]','$t[2]','$t[3]','$t[4]',0),";
							$te++;
							}
						} else {
							echo "Nie pobrano ID przystanku $przystanek, linia $line, kierunek ".$directions[($kierunek-1)]."<br>";
						}
						$nrPrzystanek++;
					}
					$nrKurs++;
				}
				$k++;
			}
		}
		$sql .= ";";
		$sql = str_replace(",;", ";", $sql);
		if($save) {
			$mysqli->query($sql);
		}
		file_put_contents("$path/odjazdy$line.sql", $sql);
		//}
	}
	//$mysqli->query("update $prefix"."odjazdy set godz = replace(godz, 'ï»¿', '');")
}



function transpose($array) {
    array_unshift($array, null);
    return call_user_func_array('array_map', $array);
}

?>