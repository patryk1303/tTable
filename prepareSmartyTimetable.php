<?php
	function prepareSmartyTimetable($line, $dirNumber, $stopID, $dayTypes) {
		$hours = hoursFromDeparturesForStop($line, $dirNumber, $stopID);
		$stopName = getStopName($stopID);
		$dirName = getDirectionName($line, $dirNumber);
		$stops = getStopsForDirection($line, $dirNumber);
		$otherLines = getLinesFromStop($stopID);
		
		$currTime = time();
		$nextTimeHour = $currTime+3600;
		
		$mins = array();
		$minsCount = array();
		$minsAndDayTypesCount = 0;
		$allMinsCount = 0;
		
		$i=0;
		foreach($dayTypes as $dayType) {
			$minsCount[] = 0;
			foreach($hours as $hour) {
				$mins[$i][] = getDeparturesForHour($line, $dirNumber, $stopID, $hour, $dayType);
			}
			if (count($mins)>0)
				foreach($mins[$i] as $min) {
					$minsCount[$i] += count($min);
				}
			$i++;
		}
		
		foreach($minsCount as $minCount) {
			$allMinsCount += $minCount;
			if($minCount != 0)
				$minsAndDayTypesCount++;
		}
		
		return array(
			"currTime" => $currTime,
			"nextTimeHour" => $nextTimeHour,
			"hours" => $hours,
			"mins" => $mins,
			"minsCount" => $minsCount,
			"minsAndDayTypesCount" => $minsAndDayTypesCount,
			"stopName" => $stopName,
			"dirName" => $dirName,
			"stops" => $stops,
			//"infos" => getInfos($line, $dirNumber),
			"date" => getLineDate($line),
			"otherLines" => $otherLines,
			"allMinsCount" => $allMinsCount,
			"announc" => getAnnouncements($line,$dirNumber,$stopID)
		);
	}
?>