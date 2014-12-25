<?php
//BARDZO WAŻNE! PLIKI Z ROZKŁADAMI MAJĄ BYĆ ZAPISANE BEZ DOM!!

//update odjazdy set godz = replace(godz, 'ï»¿', '');
//update oznaczenia set `oznaczenia` = replace(`oznaczenia`, '﻿', '');

//$saveSQL=true;
//$saveFile=true;

require_once 'utils.php';
#$lines = array(16);

function prepareDepatrues($path, $lines) {
	global $mysqli,$prefix,$saveSQL,$saveFile;
	$dayTypes = getDayTypes();
	$stopsList = getStopsAndIDs();
	$regex = "/([0-9][0-9])([a-zA-Z]*)/";
	$polskie_literki = array("ą","ę","ł","ó","ś","ć","ź","ż","ń","Ą","Ę","Ł","Ó","Ś","Ć","Ź","Ż","Ń");

	foreach ($lines as $line) {
		$sql = "INSERT INTO `$prefix"."odjazdy` (`id`,`typ_dnia_id`,`linia_id`,`przyst_id`,`kier_id`,`kurs_nr`,`odjazd`,`godz`,`min`,`oznaczenia`,`stan`) VALUES\n\t";
		$sql_routes = "INSERT INTO `$prefix"."trasy_przejazdu` (`id`,`linia_id`,`kier_id`,`przyst_id`) VALUES\n\t";

		$directions = file("$path/$line/kierunki");
		$lineID = getLineID($line);
		
		$dirNumber = 1;
		foreach($directions as $direction) {
			$dirID = getDirectionID($line,$dirNumber);
			$stopsIDs = array();
			$stopsFile = file($path."/$line/przystanki_$dirNumber");
			
			foreach($stopsFile as $stop) {
			//pobranie ID przystanku
				$stopID = NULL;
				$stop = trim($stop);
				foreach ($stopsList as $stopFromList) { //pobranie ID przystanku - metoda 1, poprzez listę asocjacyjną
					$stopFromList[1] = trim($stopFromList[1]);
					if ($stopFromList[1] == $stop) {
						$stopID = $stopFromList[0];
						break;
					}
				}
				$przy = explode("/",$stop);
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
				if (isset($stopID)) {
					$stopsIDs[] = $stopID;
				} else {
					echo "Nie pobrano ID przystanku: $stop, linia: $line, kierunek: $direction<br>";
					$stopsIDs[] = 666;
				}
			}
			
			foreach($stopsIDs as $sID) {
				//(`id`,`linia_id`,`kier_id`,`przyst_id`)
				$sql_routes .= "(NULL,$lineID,$dirID,$sID),\n\t";
			}
			
			$dayTypeNumber = 1;
			foreach($dayTypes as $dayType) {
				$departures = array();
				//$departuresFile = file($path."/$line/$dirNumber"."_$dayTypeNumber");
				$departuresFile = $path."/$line/$dirNumber"."_$dayTypeNumber";
				if (file_exists($departuresFile)){
					$departuresFile = file($departuresFile);
					foreach($departuresFile as $dF) {
						$dF = preg_replace('~[\r\n]+~', '', $dF);
						$departures[] = explode("\t",$dF);
					}
					
					$departures = transpose($departures);
					#print_r($departures);
					
					//$depsTemp = array();
					$tripNumber = 0;
					//line -> trip, each element corresponds to stop number
					foreach($departures as $departureLine) {
						$depsTemp = array();
						$stopNumber = 0;
						foreach($departureLine as $departure) {
							//if($line==23)
							//	echo strlen($departure)." - $departure<br>";
							if(strlen($departure)>2) {
								//echo "$line<br>";
								$departure1 = explode(":",$departure);
								preg_match($regex, $departure1[1], $min);
								//echo strlen($departure)." - $departure ";
								
								$depsTemp[] = array(
									"stopID" => $stopsIDs[$stopNumber],
									"dirID"  => $dirID,
									"tripNo" => $tripNumber,
									"dep"    => $departure,
									"hour"   => $departure1[0],
									"min"    => $min[1],
									"infos"  => $min[2]
								);
							}
							
							$stopNumber++;
						}
						
						$j=0;
						foreach($depsTemp as $dT) {
							if($j==	count($depsTemp)-1)
								$depStatus=1;
							elseif($j==0)
								$depStatus=2;
							else
								$depStatus=0;
							$sql .= "(NULL,$dayType[0],$lineID,{$dT['stopID']},{$dT['dirID']},{$dT['tripNo']},'{$dT['dep']}','{$dT['hour']}','{$dT['min']}','{$dT['infos']}',$depStatus),\n\t";
							
							$j++;
						}
						$tripNumber++;
					}
				}
				$dayTypeNumber++;
			}
			
			$dirNumber++;
		}
		
		$sql .= ";";
		$sql_routes .= ";";
		$sql = str_replace(",\n\t;", ";", $sql);
		$sql_routes = str_replace(",\n\t;", ";", $sql_routes);
		$sql = str_replace(",;", ";", $sql);
		$sql_routes = str_replace(",;", ";", $sql_routes);
		if($saveSQL) {
			//echo "$sql<br>";
			$mysqli->query($sql);
			$mysqli->query($sql_routes);
		}
		if($saveFile) {
			file_put_contents("$path/odjazdy$line.sql", $sql);
			file_put_contents("$path/trasy$line.sql", $sql_routes);
		}
	}
	//$mysqli->query("update $prefix"."odjazdy set godz = replace(godz, 'ï»¿', '');")
}



function transpose($array) {
    array_unshift($array, null);
    return call_user_func_array('array_map', $array);
}

?>