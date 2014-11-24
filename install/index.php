<?php
	require_once '../smarty/Smarty.class.php';
	require_once 'config.php';
	require_once 'prepareStopListSQL.php';
	require_once 'prepareLinesList.php';
	require_once 'prepareDirectionsList.php';
	require_once 'prepareDayTypes.php';
	require_once 'prepareInfos.php';
	require_once 'prepareInfos_1.php';
	require_once 'prepareNewStops.php';
	require_once 'prepareDepartures.php';
	require_once 'utils.php';
	
	
	if (!isset($_GET['step']))
		$_GET['step'] = null;

	$step = $_GET['step'];

	$tpl = new Smarty();
	
	$tpl -> assign("step",$step);
	
	switch($step) {
		case 'days':
			$dT = count(getDayTypes());
			$tpl -> assign("day_count",$dT);
			
			if($dT == 0) {
				$mysqli->query("TRUNCATE `$prefix"."typy_dni`");
				$sql = prepareDayTypes($pathToSchedule);
				$mysqli->query($sql);
			}
			
			break;
		case 'lines':
			$mysqli->query("TRUNCATE `$prefix"."linie`");
			$sql = prepareLinesList($pathToSchedule, $lines);
			$mysqli->query($sql);
			$lines = getLines();
			$tpl -> assign('lines',$lines);
			break;
		case 'directions':
			$mysqli->query("TRUNCATE `$prefix"."kierunki`");
			$sql = prepareDirectionsList($pathToSchedule, $lines);
			$mysqli->query($sql);
			$dirs = getDirections();
			$tpl -> assign('dirs',$dirs);
			break;
			
		case 'stops_install':
			$mysqli->query("TRUNCATE `$prefix"."przystanki`");
			$sql = prepareStopListSQL($pathToSchedule, $lines);
			$mysqli->query($sql);
			$stops = getStopsAndIDs();
			$tpl -> assign('stops',$stops);
			break;
		case 'stops_update':
			$sql = prepareNewStops($pathToSchedule, $lines);
			$mysqli->query($sql[0]);
			$stops = $sql[1];
			$tpl -> assign('stops',$stops);
			break;
			
		case 'infos':
			$mysqli->query("TRUNCATE `$prefix"."oznaczenia`");
			$mysqli->query("TRUNCATE `$prefix"."oznaczenia_1`");
			$sql = prepareInfos($pathToSchedule, $lines);
			$mysqli->query($sql);
			$sql = prepareInfos_1($pathToSchedule, $lines);
			$mysqli->query($sql);
			break;
			
		case 'deps':
			$saveSQL=true;
			$saveFile=false;
			$mysqli->query("TRUNCATE `$prefix"."odjazdy`");
			$mysqli->query("TRUNCATE `$prefix"."trasy_przejazdu`");
			prepareDepatrues($pathToSchedule, $lines);
			
			break;
	}
	
	$html = $tpl -> fetch('tpl/index.html');
	$html = preg_replace("#^\s+#m", '', $html);
	
	echo $html;
	
?>