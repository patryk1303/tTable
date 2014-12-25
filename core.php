<?php

session_start();

require_once 'config.php';

$lang_file = "lang/lang.$lang.php";
if (!(file_exists($lang_file)))
	$lang_file = "lang/lang.pl.php";
//echo $lang_file;	


//start session variavles
if (!isset($_SESSION['style']))
	$_SESSION['style'] = 0;

if (!isset($_GET['line']))
	$_GET['line'] = null;
//jeśli linia niewybrana - zmień wartość na null
if (!isset($_GET['dir']))
	$_GET['dir'] = null;
//jeśli kierunek niewybrany - zmień wartość na null
if (!isset($_GET['stop']))
	$_GET['stop'] = null;
//jeśli kurs niewybrany - zmień wartość na null
if (!isset($_GET['course']))
	$_GET['course'] = null;
//jeśli dzień niewybrany - zmień wartość na null
if (!isset($_GET['day']))
	$_GET['day'] = null;
if (!isset($_GET['print']))
	$_GET['print'] = null; 
if (!isset($_GET['list']))
	$_GET['list'] = null; 
if (!isset($_GET['ending']))
	$_GET['ending'] = null;
if (isset($_GET['style'])) {
	$_SESSION['style'] = $_GET['style'];
	//$_GET['style'] = null;
}

$wLine = $_GET['line'];
$wDir  = $_GET['dir'];
$wStop = $_GET['stop'];
$wCourse = $_GET['course'];
$wDay = $_GET['day'];
$wPrint = $_GET['print'];
$wList = $_GET['list'];
$wEnding = $_GET['ending'];
//$wStyle = $_GET['style'];
$currUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
//$currUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[PHP_SELF]";
//$currUrl = preg_replace("/&style=\d{1,}","",$currUrl);
$currUrl = str_replace("&ending=1","",$currUrl);
$currUrl = str_replace("&style=0","",$currUrl);
$currUrl = str_replace("&style=1","",$currUrl);
$currUrl = str_replace("&style=2","",$currUrl);
$currUrl = str_replace("&style=3","",$currUrl);
$currUrl = str_replace("&style=4","",$currUrl);
$currUrl = str_replace("&style=5","",$currUrl);
$currUrl = str_replace("&style=6","",$currUrl);
$self = $_SERVER['PHP_SELF'];
//echo "$self<br>";
$self = str_replace("/index.php","",$self);
//echo "$self<br>";

//print_r($_SERVER);

//echo $currUrl."<br>";
function getLines() {
	global $mysqli,$prefix;
	//pobranie linii z bazy danych
	$lines = array();
	$sql = "SELECT * FROM $prefix"."linie AS linie";
	$result = $mysqli->query($sql);
	while($row = $result->fetch_assoc())
		$lines[] = $row['linia'];
	return $lines;
}

function getStops() {
	global $mysqli,$prefix;
	//pobranie przystaków z bazy danych
	$stops = array();
	$sql = "
	SELECT
		nazwa1,nazwa2,nr_urzedowy,nz,id
	FROM
		$prefix"."przystanki AS przystanki
	ORDER BY
		nazwa1 ASC,
		nazwa2 ASC
		";
	$result = $mysqli->query($sql);
	while($row = $result->fetch_assoc())
		$stops[] = array($row['nazwa1'],$row['nazwa2'],$row['nr_urzedowy'],$row['nz'],$row['id']);
	return $stops;
}

/*function getDayTypes() {
	global $mysqli;
	//pobranie typów dni z bazy danych
	$dayTypes = array();
	$sql = "SELECT nazwa FROM typy_dni";
	$result = $mysqli->query($sql);
	while($row = $result->fetch_assoc())
		$dayTypes[] = $row['nazwa'];
	return $dayTypes;
}*/

//jeżeli wybrano linię ale nie kierunek oraz przystanek, to pobierz kierunki i ich id
function getDirections($line) {
	global $mysqli,$prefix;
	$directions = array();
	$lineID = getLineID($line);
	$sql = "
	SELECT
		kierunki.id, kierunki.nr_kier, kierunki.kierunek, kierunki.linia_id
	FROM
		$prefix"."kierunki AS kierunki, $prefix"."linie as linie
	WHERE
		kierunki.linia_id = linie.id AND
		kierunki.linia_id = $lineID";
	//$sql = "SELECT kierunki.id AS id, linie.linia AS linia, kierunki.kierunek AS kierunek FROM kierunki, linie WHERE kierunki.id_linia=$lineID AND linia='".$line."'";
	//echo $sql;
	$result = $mysqli->query($sql);
	while($row = $result->fetch_assoc())
		$directions[] = array($row['id'],$row['nr_kier'],$row['kierunek']);
	return $directions;
} 

//pobranie przystanków dla wybranego kierunki linii
function getStopsForDirection($line, $dir) {
	global $mysqli,$prefix;
	$stops = array();
	$lineID = getLineID($line);
	$dirID = getDirectionID($line, $dir);
	$sql = "
SELECT DISTINCT
	przystanki.id, 
	przystanki.nazwa1, 
	przystanki.nazwa2, 
	przystanki.nr_urzedowy, 
	przystanki.nz, 
	linie.linia, 
	kierunki.nr_kier, 
	trasy_przejazdu.id AS route_id
FROM
	$prefix"."odjazdy AS odjazdy, 
	$prefix"."przystanki AS przystanki, 
	$prefix"."linie AS linie, 
	$prefix"."kierunki AS kierunki, 
	$prefix"."trasy_przejazdu AS trasy_przejazdu
WHERE odjazdy.przyst_id = przystanki.id AND
	odjazdy.linia_id = linie.id AND
	kierunki.id = odjazdy.kier_id AND
	trasy_przejazdu.linia_id = linie.id AND
	trasy_przejazdu.kier_id = kierunki.id AND
	trasy_przejazdu.przyst_id = przystanki.id AND
	odjazdy.linia_id = $lineID AND
	odjazdy.kier_id = $dirID
ORDER BY
	route_id ASC
	";
	//echo $sql;
	/*$sql = "SELECT DISTINCT przystanki.id, przystanki.nazwa1, przystanki.nazwa2, przystanki.nr_urzedowy, przystanki.nz
		FROM $prefix"."odjazdy AS odjazdy, $prefix"."przystanki AS przystanki, $prefix"."linie AS linie, $prefix"."kierunki as kierunki
		WHERE 	odjazdy.przyst_id = przystanki.id AND
				odjazdy.linia_id = linie.id AND
				kierunki.id = odjazdy.kier_id AND
				odjazdy.linia_id = $lineID AND
				odjazdy.kier_id = $dirID
		";*/
	//echo $sql;
	$result = $mysqli->query($sql);
	while($row = $result->fetch_assoc())
		$stops[] = array($row['id'],$row['nazwa1'],$row['nazwa2'],$row['nr_urzedowy'],$row['nz']);
	return $stops;
}

function getDepartures($line,$dir,$stop,$dayType) {
	global $mysqli,$prefix,$wEnding;
	$lineID = getLineID($line);
	$dirID = getDirectionID($line, $dir);
	$deps = array();
	$sql = "
	SELECT
		odjazdy.godz, odjazdy.min, odjazdy.oznaczenia, odjazdy.kurs_nr, odjazdy.stan
	FROM
		$prefix"."odjazdy as odjazdy, $prefix"."linie as linie, $prefix"."kierunki as kierunki, $prefix"."przystanki as przystanki, $prefix"."typy_dni as typy_dni
	WHERE
		odjazdy.linia_id = linie.id AND
		kierunki.linia_id = linie.id AND
		odjazdy.kier_id = kierunki.id AND
		odjazdy.przyst_id = przystanki.id AND
		odjazdy.typ_dnia_id = typy_dni.id AND
		odjazdy.typ_dnia_id = $dayType[0] AND
		odjazdy.linia_id = $lineID AND
		odjazdy.przyst_id = $stop AND
		odjazdy.kier_id = $dirID";
	if($wEnding==null)
		$sql.= "AND odjazdy.stan != 1";
	$sql.= " ORDER BY odjazdy.min ASC";
	//echo $sql;
	$result = $mysqli->query($sql);
	while($row = $result->fetch_assoc()) {
		$deps[] = array($row['godz'],$row['min'],$row['oznaczenia'],$row['kurs_nr'],$dayType[0],$row['stan']);
	}
	return $deps;
}

function getDeparturesForHour($line,$dir,$stop,$hour,$dayType) {
	global $mysqli,$prefix,$wEnding;
	$lineID = getLineID($line);
	$dirID = getDirectionID($line, $dir);
	$deps = array();
	$sql = "
	SELECT 
		odjazdy.min, odjazdy.oznaczenia, odjazdy.kurs_nr, odjazdy.stan
	FROM
		$prefix"."odjazdy AS odjazdy, $prefix"."linie AS linie, $prefix"."kierunki AS kierunki, $prefix"."przystanki AS przystanki, $prefix"."typy_dni AS typy_dni
	WHERE
		odjazdy.linia_id = linie.id AND
		kierunki.linia_id = linie.id AND
		odjazdy.kier_id = kierunki.id AND
		odjazdy.przyst_id = przystanki.id AND
		odjazdy.typ_dnia_id = typy_dni.id AND
		odjazdy.godz = '$hour' AND
		odjazdy.typ_dnia_id = $dayType[0] AND
		odjazdy.linia_id = $lineID AND
		odjazdy.przyst_id = $stop AND
		odjazdy.kier_id = $dirID";
	if($wEnding==null)
		$sql.= " AND odjazdy.stan != 1";
	$sql.= " ORDER BY odjazdy.min ASC";
	//echo $sql;
	$result = $mysqli->query($sql);
	while($row = $result->fetch_assoc()) {
		$deps[] = array($row['min'],$row['oznaczenia'],$row['kurs_nr'],$dayType[0],$row['stan']);
	}
	return $deps;
}


function getCourse($line,$dir,$dayType,$courseNumber) {
	global $mysqli,$prefix;
	$lineID = getLineID($line);
	$dirID = getDirectionID($line, $dir);
	$deps = array();
	$sql = "
	SELECT
		odjazdy.godz, odjazdy.min, odjazdy.oznaczenia, przystanki.id, przystanki.nazwa1, przystanki.nazwa2, przystanki.nr_urzedowy, przystanki.nz
	FROM
		$prefix"."odjazdy AS odjazdy, $prefix"."linie AS linie, $prefix"."kierunki AS kierunki, $prefix"."przystanki AS przystanki, $prefix"."typy_dni AS typy_dni
	WHERE
		odjazdy.linia_id = linie.id AND
		kierunki.linia_id = linie.id AND
		odjazdy.kier_id = kierunki.id AND
		odjazdy.przyst_id = przystanki.id AND
		odjazdy.typ_dnia_id = typy_dni.id AND
		odjazdy.kurs_nr = $courseNumber AND
		odjazdy.typ_dnia_id = $dayType[0] AND
		odjazdy.linia_id = $lineID AND
		odjazdy.kier_id = $dirID";
	//echo $sql;
	$result=$mysqli->query($sql);
	while($row = $result->fetch_assoc()) {
		$deps[] = array($row['godz'],$row['min'],$row['oznaczenia'],$row['nazwa1'],$row['nazwa2'],$row['nr_urzedowy'],$row['nz'],$row['id']);
	}
	return $deps;
}

function getLastStopInTrip($line,$dir,$dayType,$tripNumber) {
	global $mysqli,$prefix;
	$lineID = getLineID($line);
	$dirID = getDirectionID($line,$dir);
	$sql = "
	SELECT
		przystanki.nazwa1,
		przystanki.nazwa2,
		przystanki.nr_urzedowy,
		przystanki.nz,
		odjazdy.id AS oid
	FROM
		$prefix"."odjazdy AS odjazdy,
		$prefix"."linie AS linie,
		$prefix"."kierunki AS kierunki,
		$prefix"."przystanki AS przystanki,
		$prefix"."typy_dni AS typy_dni
	WHERE odjazdy.linia_id = linie.id AND
		kierunki.linia_id = linie.id AND
		odjazdy.kier_id = kierunki.id AND
		odjazdy.przyst_id = przystanki.id AND
		odjazdy.typ_dnia_id = typy_dni.id AND
		odjazdy.kurs_nr = $tripNumber AND
		odjazdy.typ_dnia_id = $dayType[0] AND
		odjazdy.linia_id = $lineID AND
		odjazdy.kier_id = $dirID
	ORDER BY oid DESC LIMIT 1";
	//echo $sql;
	$result=$mysqli->query($sql);
	$row = $result->fetch_assoc();
	return array($row['nazwa1'],$row['nazwa2'],$row['nr_urzedowy'],$row['nz']);
}

function getLinesFromStop($stop) {
	global $mysqli,$prefix;
	if(!is_array($stop)) {
		$lines = array();
		$sql = "
		SELECT DISTINCT
			linie.linia, kierunki.nr_kier, kierunki.kierunek
		FROM
			$prefix"."odjazdy AS odjazdy, $prefix"."linie AS linie, $prefix"."kierunki AS kierunki, $prefix"."przystanki AS przystanki
		WHERE
			odjazdy.przyst_id = przystanki.id AND
			odjazdy.linia_id = linie.id AND
			odjazdy.kier_id = kierunki.id AND
			odjazdy.przyst_id = $stop";
		$result=$mysqli->query($sql);
		while($row = $result->fetch_assoc()) {
			$lines[] = array($row['linia'],$row['nr_kier'],$row['kierunek']);
		}
		return $lines;
	}
	else {
		$lines = array();
		foreach ($stop as $s) {
			$linesA = array();
			$sql = "
			SELECT DISTINCT
				linie.linia, kierunki.nr_kier, kierunki.kierunek
			FROM
				$prefix"."odjazdy AS odjazdy, $prefix"."linie AS linie, $prefix"."kierunki AS kierunki, $prefix"."przystanki AS przystanki
			WHERE
				odjazdy.przyst_id = przystanki.id AND
				odjazdy.linia_id = linie.id AND
				odjazdy.kier_id = kierunki.id AND
				odjazdy.przyst_id = $s";
			$result=$mysqli->query($sql);
			while($row = $result->fetch_assoc()) {
				$linesA[] = array($row['linia'],$row['nr_kier'],$row['kierunek']);
			}
			$lines[] = $linesA;
		}
		return $lines;
	}
}

function hoursFromDeparturesForStop($line,$dir,$stop) {
	global $mysqli,$prefix,$wEnding;
	$lineID = getLineID($line);
	$dirID = getDirectionID($line, $dir);
	$hours = array();
	$sql = "
	SELECT DISTINCT
		odjazdy.godz
	FROM
		$prefix"."odjazdy AS odjazdy, $prefix"."linie AS linie, $prefix"."kierunki AS kierunki, $prefix"."przystanki AS przystanki, $prefix"."typy_dni AS typy_dni
	WHERE
		kierunki.linia_id = linie.id AND
		odjazdy.kier_id = kierunki.id AND
		odjazdy.linia_id = linie.id AND
		odjazdy.typ_dnia_id = typy_dni.id AND
		odjazdy.przyst_id = przystanki.id AND
		odjazdy.linia_id = $lineID AND
		odjazdy.przyst_id = $stop AND
		odjazdy.kier_id = $dirID";
	if($wEnding==null)
		$sql.= " AND odjazdy.stan != 1";
	$sql .= " ORDER BY CAST(godz as SIGNED INTEGER) ASC";
	$result = $mysqli->query($sql);
	while($row = $result->fetch_assoc()) {
		$hours[] = $row['godz'];
	}
	return $hours;
}

function getDayTypes() {
	global $mysqli,$prefix;
	$dayTypes = array();
	$sql = "SELECT * FROM $prefix"."typy_dni AS typy_dni";
	$result = $mysqli->query($sql);
	while($row = $result->fetch_assoc())
		$dayTypes[] = array($row['id'],$row['nazwa']);
	return $dayTypes;
}


function getLineID($line) {
	global $mysqli,$prefix;
	$sql = "SELECT id FROM $prefix"."linie AS linie WHERE linia='$line'";
	$result = $mysqli->query($sql);
	while($row = $result->fetch_assoc()) {
		$lineID = $row['id'];
	}
	
	//$lineID = mysql_result($result, 0);
	return $lineID;
}

function getDirectionID($line,$dirNumber) {
	global $mysqli,$prefix;
	$lineID = getLineID($line);
	$sql = "SELECT id FROM $prefix"."kierunki AS kierunki WHERE linia_id=$lineID AND nr_kier=$dirNumber";
	$result = $mysqli->query($sql);
	while($row = $result->fetch_assoc()) {
		$dirID = $row['id'];
	}
	//$dirID = $result->fetch_assoc()['id'];
	return $dirID;
}
function getDirectionName($line,$dirNumber) {
	global $mysqli,$prefix;
	$lineID = getLineID($line);
	$sql = "SELECT kierunek FROM $prefix"."kierunki AS kierunki WHERE linia_id=$lineID AND nr_kier=$dirNumber";
	$result = $mysqli->query($sql);
	while($row = $result->fetch_assoc()) {
		$dirName = $row['kierunek'];
	}
	//$dirName = $result->fetch_assoc()['kierunek'];
	return $dirName;
}

function getStopName($stopID) {
	global $mysqli,$prefix;
	$sql = "SELECT nazwa1, nazwa2, nr_urzedowy, nz FROM $prefix"."przystanki AS przystanki WHERE id=$stopID";
	$result = $mysqli->query($sql);
	while($row =$result->fetch_assoc()) {
		$stopName = array($row['nazwa1'],$row['nazwa2'],$row['nr_urzedowy'],$row['nz']);
	}
	return $stopName;
}

/*function getInfos($line,$dirNumber) {
	global $mysqli,$prefix;
	$lineID = getLineID($line);
	$dirID = getDirectionID($line, $dirNumber);
	$sql = "
	SELECT
		oznaczenia.oznaczenia
	FROM
		$prefix"."oznaczenia AS oznaczenia, $prefix"."linie AS linie, $prefix"."kierunki as kierunki
	WHERE
		oznaczenia.linia_id = linie.id AND
		oznaczenia.kier_id = kierunki.id AND
		oznaczenia.linia_id = $lineID AND
		oznaczenia.kier_id = $dirID";
	$result = $mysqli->query($sql);
	while($row = $result->fetch_assoc()) {
		$infos = $row['oznaczenia'];
	}
	return $infos;
	//return $result->fetch_assoc()['oznaczenia'];
}*/

function getInfos_1($line,$dirNumber) {
	global $mysqli,$prefix;
	$infos = array();
	$lineID = getLineID($line);
	$dirID = getDirectionID($line, $dirNumber);
	$sql = "
	SELECT DISTINCT
		oznaczenia_1.tekst, 
		oznaczenia_1.oznaczenie, 
		oznaczenia_1.opis
	FROM
		$prefix"."odjazdy AS odjazdy, 
		$prefix"."linie AS linie, 
		$prefix"."kierunki AS kierunki, 
		$prefix"."przystanki AS przystanki, 
		$prefix"."typy_dni AS typy_dni, 
		$prefix"."oznaczenia_1 AS oznaczenia_1
	WHERE
		odjazdy.linia_id = linie.id AND
		kierunki.linia_id = linie.id AND
		odjazdy.kier_id = kierunki.id AND
		odjazdy.przyst_id = przystanki.id AND
		odjazdy.typ_dnia_id = typy_dni.id AND
		oznaczenia_1.linia_id = linie.id AND
		oznaczenia_1.kier_id = kierunki.id AND
		odjazdy.linia_id = $lineID AND
		odjazdy.kier_id = $dirID";
	$result = $mysqli->query($sql);
	while($row = $result->fetch_assoc()) {
		$infos[] = array($row['tekst'],$row['oznaczenie'],$row['opis']);
	}
	return $infos;
	//return $result->fetch_assoc()['oznaczenia'];
}

function getLineDate($line) {
	global $mysqli,$prefix;
	$lineID = getLineID($line);
	$sql = "
	SELECT
		data
	FROM
		$prefix"."linie AS linie
	WHERE
		linie.id=$lineID";
	$result = $mysqli->query($sql);
	while($row = $result->fetch_assoc()) {
		$date = $row['data'];
	}
	//return $result->fetch_assoc()['data'];
	return $date;
}

function getAllDeparturesForStopAndDayType($stopID,$dayTypeID) {
	global $mysqli,$prefix,$wEnding;
	$departures = array();
	$sql = "
	SELECT
		linie.linia,
		odjazdy.godz,
		odjazdy.min,
		odjazdy.oznaczenia,
		odjazdy.kurs_nr,
		kierunki.kierunek,
		kierunki.nr_kier
	FROM
		$prefix"."kierunki AS kierunki,
		$prefix"."linie AS linie,
		$prefix"."odjazdy AS odjazdy,
		$prefix"."przystanki AS przystanki,
		$prefix"."typy_dni AS typy_dni
	WHERE
		odjazdy.linia_id = linie.id AND
		odjazdy.kier_id = kierunki.id AND
		odjazdy.przyst_id = przystanki.id AND
		odjazdy.typ_dnia_id = typy_dni.id AND
		odjazdy.przyst_id = $stopID AND
		odjazdy.typ_dnia_id = $dayTypeID";
	if($wEnding==null)
		$sql.= " AND odjazdy.stan != 1";
	$sql .= " ORDER BY
		odjazdy.godz*1 ASC,
		odjazdy.min*1 ASC
	";
	//echo $sql;
	$result = $mysqli->query($sql);
	while($row = $result->fetch_assoc()) {
		//echo $row['kierunek']."<br>";
		//$infos = getInfos_1($row['linia'],$row['nr_kier']);
		//$infosSigns = str_split($row['oznaczenia']);
		//$currentSigns = getCurrentSigns($infosSigns,$infos);
		//getLastStopInTrip($line,$dir,$dayType,$tripNumber)
		$lastStop = getLastStopInTrip($row['linia'],$row['nr_kier'],$dayTypeID,$row['kurs_nr']);
		//print_r($infosSigns);
		$departures[] = array(
			"line" => $row['linia'],
			"hour" => $row['godz'],
			"min"  => $row['min'],
			//"info" => $row['oznaczenia'],
			"dir"  => $row['kierunek'],
			//"infos"=> $row['ozn'],
			"trip" => $row['kurs_nr'],
			"dir_n"=> $row['nr_kier'],
			//"info1"=> $currentSigns,
			"last_stop" => $lastStop
		);
	}
	return $departures;
}

function getAnnouncements($line=NULL,$dir=NULL,$stopID=NULL) {
	if($line==NULL and $dir==NULL and $stopID==NULL)
		return null;
	global $mysqli,$prefix;
	$ret = array();
	$sql = "SELECT message FROM $prefix"."announcements";
	if($line!=NULL)
		$sql .= " WHERE line_id=".getLineID($line);
	if($dir!=NULL)
		$sql .= " OR dir_id=".getDirectionID($line,$dir);
	if($line!=NULL and $dir!=NULL and $stopID!=NULL)
		$sql .= " OR stop_id=$stopID";
	if($line==NULL and $dir==NULL)
		$sql .= " WHERE stop_id=$stopID AND line_id IS NULL AND dir_id IS NULL";
	//$sql.= " WHERE line_id=$line AND dir_id=$dir AND stop_id=$stopID";
	//echo $sql;
	$result = $mysqli->query($sql);
	while($row = $result->fetch_assoc()) {
		$ret[] = array(
			/*"id"			=>	$row["id"],
			"line_id"		=>	$row["line_id"],
			"dir_no"		=>	$row["dir_no"],
			"stop_id"	=>	$row["stop_id"],*/
			"message"	=>	$row["message"]
		);
	}
	return $ret;
}

/**
 * @param stop stop name in this way: <b>array($district,$name,$number,$isOnDemand)
 */
function writeFullStopName($stop) {
	$nz = $stop[0];
	if($stop[1] != '') {
		$nz .= ' / ' . $stop[1];
	}
	if($stop[3] == "1") {
		$nz .= ' - <span class="nz">nż</span>';
	}
	if($stop[2] > '0')
		$nz .= ' '.$stop[2];
	
	return $nz;
}

/**
	@param $ret '<i>mins</i>' index from function <u>prepareSmartyTimetable</u>
	@see prepareSmartyTimetable
*/
function getInfosSignsFromRet($ret) {
	$infos = array();
	$infos_ret = array();
	$infos_ret1 = array();
	$temp = array();
	foreach($ret as $min) {
		foreach($min as $m) {
			foreach($m as $m1) {
				$infos[] = $m1[1];
			}
		}
	}
	$infos = array_unique($infos);
	foreach($infos as $info) {
		$temp[] = str_split($info);
	}
	
	foreach($temp as $t) {
		foreach($t as $t1) {
			$infos_ret[] = $t1;
		}
	}
	$infos_ret = array_unique($infos_ret);
	
	foreach($infos_ret as $r) {
		if(strlen($r)>0)
			$infos_ret1[] = $r;
	}
	//print_r($infos_ret);
	return $infos_ret1;
}

function getCurrentSigns($infosSigns,$infos1) {
	$currentSigns = array();
	foreach($infos1 as $i1) {
		$lineSigns[] = $i1[1];
		$lineSignsTexts[] = $i1[2];
	}
	if(!isset($lineSigns))
		return;
	for($i=0;$i<count($lineSigns);$i++) {
		if (in_array($lineSigns[$i],$infosSigns)) {
			$currentSigns[] = array($lineSigns[$i],$lineSignsTexts[$i]);
		}
	}
	return $currentSigns;
}

function getLocXYForClockTimetable() {
	$mins = array();
	$mins2 = array();
	
	for($i=0;$i<60;$i++) {
		$alfa = $i*pi()/30 - pi()/2;
		$mins[$i][0] = floor(50*cos($alfa));
		$mins[$i][1] = floor(50*sin($alfa));
		
		$mins2[$i][0] = floor(65*cos($alfa));
		$mins2[$i][1] = floor(65*sin($alfa));
	}
	return array($mins,$mins2);
}

function getLocXYForClockTimetableHours() {
	$hrs = array();
	$hrs2 = array();
	
	for($i=0;$i<24;$i++) {
		$alfa = $i*pi()/12 - pi()/2;
		$hrs[$i][0] = floor(250*cos($alfa)) + 230;
		$hrs[$i][1] = floor(250*sin($alfa)) + 5;
		
		$hrs2[$i][0] = floor(300*cos($alfa)) + 230;
		$hrs2[$i][1] = floor(300*sin($alfa)) + 5;
	}
	return array($hrs,$hrs2);
}

?>