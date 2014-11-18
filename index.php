<?php

//echo "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]<br>";

require_once 'config.php';
require_once 'core.php';
require_once 'smarty/Smarty.class.php';
require_once 'prepareSmartyTimetable.php';
require_once $lang_file;

$tpl = new Smarty();
$tpl -> assign("lang", $lang);
$tpl -> assign("wLine", $wLine);
$tpl -> assign("wDir", $wDir);
$tpl -> assign("wStop", $wStop);
$tpl -> assign("wCourse", $wCourse);
$tpl -> assign("wDay", $wDay);
$tpl -> assign("wPrint", $wPrint);
$tpl -> assign("wList", $wList);
$tpl -> assign("wEnding", $wEnding);
//$tpl -> assign("wStyle", $wStyle);
$tpl -> assign("currUrl", $currUrl);
$tpl -> assign("self", $self);

$dayTypes = getDayTypes();
$lines = getLines();

$tpl -> assign("dayTypes", $dayTypes);
$tpl -> assign("lines", $lines);

$isNothyingSelected = ($wLine == null and $wDir == null and $wStop == null and $wCourse == null and $wList == null and $wDay == null);
$isChosenLine = ($wLine != null and $wDir == null and $wStop == null and $wCourse == null and $wList == null and $wDay == null);
$isChosenStop = ($wLine == null and $wDir == null and $wStop != null and $wCourse == null and $wList == null and $wDay == null);
$isChosenAllDeparturesForStopOneDay = ($wLine == null and $wDir == null and $wStop != null and $wCourse == null and $wList == null and ($wDay != null and $wDay!="all"));
$isChosenAllDeparturesForStopAllDays = ($wLine == null and $wDir == null and $wStop != null and $wCourse == null and $wList == null and ($wDay != null and $wDay=="all"));
$isChosenStopList = ($wLine == null and $wDir == null and $wStop == null and $wCourse == null and $wList != null and $wDay == null);
$isChosenLineAndDirection = ($wLine != null and $wDir != null and $wStop == null and $wCourse == null and $wList == null and $wDay == null);
$isChosenLineDirectionAndStop = ($wLine != null and $wDir != null and $wStop != null and $wCourse == null and $wList == null and $wDay == null);
$isChosenTrip = ($wLine != null and $wDir != null and $wStop != null and $wCourse != null and $wList == null and $wDay != null);

$tpl -> assign('isNothyingSelected',$isNothyingSelected);
$tpl -> assign('isChosenLine',$isChosenLine);
$tpl -> assign('isChosenStop',$isChosenStop);
$tpl -> assign('isChosenAllDeparturesForStopOneDay',$isChosenAllDeparturesForStopOneDay);
$tpl -> assign('isChosenAllDeparturesForStopAllDays',$isChosenAllDeparturesForStopAllDays);
$tpl -> assign('isChosenStopList',$isChosenStopList);
$tpl -> assign('isChosenLineAndDirection',$isChosenLineAndDirection);
$tpl -> assign('isChosenLineDirectionAndStop',$isChosenLineDirectionAndStop);
$tpl -> assign('isChosenTrip',$isChosenTrip);

$currTime = time();
$tpl -> assign("currTime",$currTime);

$clockMins = getLocXYForClockTimetable();
$clockHrs = getLocXYForClockTimetableHours();
$tpl -> assign('mins1',$clockMins[0]);
$tpl -> assign('mins2',$clockMins[1]);
$tpl -> assign('hrs1',$clockHrs[0]);
$tpl -> assign('hrs2',$clockHrs[1]);

//Pobranie kierunków i przystanków tych kierunków wybranej linii
if ($isChosenLine) {
	$directions = getDirections($wLine);
	$stops = array();
	foreach ($directions as $dir) {
		$stops[] = getStopsForDirection($wLine, $dir[1]);
	}
	$tpl -> assign("directions", $directions);
	$tpl -> assign("stops", $stops);
}

//Pobranie kursów linii kursujących z danego przystanku
if ($isChosenStop) {
	if(!is_array($wStop)) {
		$allLines = getLinesFromStop($wStop);
		$allLinesInfo = array();
	
		foreach($allLines as $line) {
			$temp = prepareSmartyTimetable($line[0], $line[1], $wStop, $dayTypes);
			//print_r($temp); echo "<br>";
			$infosSigns = getInfosSignsFromRet($temp['mins']);
			$infos1 = getInfos_1($line[0],$line[1]);
			$currentSigns = getCurrentSigns($infosSigns,$infos1);
			//print_r($currentSigns);
			$allLinesInfo[] = array(
				$line[0], //line
				$line[1], //direction number
				$temp,
				$currentSigns
			);
		}
		$tpl -> assign("allLinesInfo", $allLinesInfo);
	}
	else {
		$allLinesInfo = array();
		foreach ($wStop as $stop) {
			$allLines = getLinesFromStop($stop);
		
			foreach($allLines as $line) {
				$temp = prepareSmartyTimetable($line[0], $line[1], $stop, $dayTypes);
				//print_r($temp); echo "<br>";
				$allLinesInfo[] = array(
					$line[0], //line
					$line[1], //direction number
					$temp
				);
			}
		}
		$tpl -> assign("allLinesInfo", $allLinesInfo);
		//echo(count($allLinesInfo));
	}
}

//pobranie kolejnych odjazdów dla wybranego przystanku i typu dnia
if ($isChosenAllDeparturesForStopOneDay) {
	$departures = getAllDeparturesForStopAndDayType($wStop, $wDay);
	$stopName = getStopName($wStop);
	$infos = array();
	
	$nextTimeHour = $currTime+3600;
	
	//$tpl -> assign("currTime",$currTime);
	$tpl -> assign("nextTimeHour",$nextTimeHour);
	$tpl -> assign("stopName", $stopName);
	$tpl -> assign("departures", $departures);
}

//pobranie kolejnych odjazdów dla wybranego przystanku i wszystkich typów dnia
if ($isChosenAllDeparturesForStopAllDays) {
	$departures = array();
	foreach($dayTypes as $dT) {
		//echo $dayTypes;
		$departures[] = getAllDeparturesForStopAndDayType($wStop, $dT[0]);
	}
	$stopName = getStopName($wStop);
	//$infos = array();
	
	$currTime = time();
	$nextTimeHour = $currTime+3600;
	
	//$tpl ->
	$tpl -> assign("currTime",$currTime);
	$tpl -> assign("nextTimeHour",$nextTimeHour);
	$tpl -> assign("stopName", $stopName);
	$tpl -> assign("departures1", $departures);
}

//Pobranie wszystkich przystanków i linii z nich kursujących
if ($isChosenStopList) {
	$stopsAndLines = array();
	$stops = getStops();
	foreach($stops as $stop) {
		$temp = array();
		$lines = getLinesFromStop($stop[4]);
		
		if(count($lines) > 0) {
			$temp['stop'] = array($stop[0],$stop[1],$stop[2],$stop[3],$stop[4]);

			foreach($lines as $line) {
				$temp['lines'][] = $line[0];
			}
			$temp['lines'] = array_unique($temp['lines']);
			//echo ("$stop[0]/$stop[1] - $stop[4] (".count($lines).")<br>");
			$stopsAndLines[] = array($temp['stop'],$temp['lines']);
		}
	}	
	
	$tpl -> assign("stopsAndLines",$stopsAndLines);
	
	//print_r($stopsAndLines);
}

//Pobranie kursów dla wszystkich przystanków dla wybranej linii i kierunku
if ($isChosenLineAndDirection) {
	$allDeps = getStopsForDirection($wLine, $wDir);
	$allDepsInfo = array();

	foreach($allDeps as $dep) {
		$temp = prepareSmartyTimetable($wLine, $wDir, $dep[0], $dayTypes);
		$infosSigns = getInfosSignsFromRet($temp['mins']);
		$infos1 = getInfos_1($wLine,$wDir);
		$currentSigns = getCurrentSigns($infosSigns,$infos1);
		//print_r($temp); echo "<br>";
		$allDepsInfo[] = array(
			$dep[0], //stop ID
			$temp,
			$currentSigns
		);
	}

	$tpl -> assign("allDepsInfo", $allDepsInfo);
}

//Pobranie przystanków dla wybranej linii, kierunku i przystanku
if ($isChosenLineDirectionAndStop) {
	$ret = prepareSmartyTimetable($wLine, $wDir, $wStop, $dayTypes);
	
	$lineSigns = array();
	$lineSignsTexts = array();
	//$currentSigns = array();
	
	//print_r($ret['mins']);
	$infosSigns = getInfosSignsFromRet($ret['mins']);
	$infos1 = getInfos_1($wLine,$wDir);
	$currentSigns = getCurrentSigns($infosSigns,$infos1);
	//print_r($currentSigns);
	
	$tpl -> assign("currTime", $ret['currTime']);
	$tpl -> assign("nextTimeHour", $ret['nextTimeHour']);
	$tpl -> assign("hours", $ret['hours']);
	$tpl -> assign("mins", $ret['mins']);
	$tpl -> assign("minsCount", $ret['minsCount']);
	$tpl -> assign("minsAndDayTypesCount", $ret['minsAndDayTypesCount']);
	$tpl -> assign("stopName", $ret['stopName']);
	$tpl -> assign("dirName", $ret['dirName']);
	$tpl -> assign("stops", $ret['stops']);
	//$tpl -> assign("infos", $ret['infos']);
	$tpl -> assign("currentSigns", $currentSigns);
	$tpl -> assign("date", $ret['date']);
	$tpl -> assign("otherLines", $ret['otherLines']);
	$tpl -> assign("allMinsCount", $ret['allMinsCount']);
}

//Pobranie wybranego przebiegu wybranej linii, kierunku
if ($isChosenTrip) {
	$course = array();
	foreach (getCourse($wLine, $wDir, $wDay, $wCourse) as $dep) {
		$course[] = $dep;
	}
	//print_r(getLastStopInTrip($wLine, $wDir, $wDay, $wCourse));
	$tpl -> assign("course", $course);
}

$html = $tpl -> fetch("tpl/index_1.html");
$html = preg_replace("#^\s+#m", '', $html);
if ($wPrint == "pdf" or $wPrint == "pdf5") {
	include ("mpdf/mpdf.php");

	$pdf = $wPrint=="pdf"?
		new mPDF('', 'A4-L', '', '', 5, 5, 15, 15):
		new mPDF('', 'A5', '', '', 5, 5, 15, 15) ;
	$pdf->keep_table_proportions = true;
	//echo $html;
	$pdf -> WriteHTML($html);
	$pdf -> Output();
} else {
	//require_once 'format.php';
	
	//$format = new Format();
	
	//$out = $format->HTML($html);
	//echo $out;
	echo $html;
}
?>