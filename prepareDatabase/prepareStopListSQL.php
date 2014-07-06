<?php
//echo $pathToSchedule;

function prepareStopListSQL($path, $lines) {
	$stops = array();
	$stops1 = array();

	foreach ($lines as $line) {
		//echo "linia $line<br>";
		$dirCount = count(file("$path/$line/kierunki"));
		//echo "$line - $dirCount<br>";
		for($i=1;$i<=$dirCount;$i++) 
			$stops[] = file("$path/$line/przystanki_$i");
		//$stops[] = file("$path/$line/przystanki_1");
		//$stops[] = file("$path/$line/przystanki_2");
	}

	foreach ($stops as $stop) {
		/*for($i=0;$i<count($stop);$i++) {
			$stops1[] = $stop[$i];
		}*/
		foreach ($stop as $s) {
			$stops1[] = trim($s);
			//echo $s." - ";
		}
	}
	$stops1 = array_unique($stops1);
	sort($stops1);
	
	$sql = "INSERT INTO `przystanki` (`id`,`nazwa_pelna`,`nazwa1`,`nazwa2`,`nr_urzedowy`,`nz`) VALUES ";

	$i = 0;
	foreach ($stops1 as $stop) {
		$stop = trim($stop);
		$stop1 = explode("/", $stop, 2);
		if (count($stop1) == 1) {
			if (strpos($stop1[0],'nż') !== false) {
				$nz=1;
				$stop1[0] = str_replace(" – nż","",$stop1[0]);
				$stop1[0] = str_replace(" - nż","",$stop1[0]);
			} else {
				$nz=0;
			}
			//$stop[0] = trim($stop[0]);
			if ($i != count($stops1) - 1)
				$sql .= "(NULL,'$stop','$stop1[0]','','',$nz),";
			else
				$sql .= "(NULL,'$stop','$stop1[0]','','',$nz)";
		} else {
			//echo $stop[0]."--".$stop[1],"<br>";
			$stop1[0] = trim($stop1[0]);
			$stop1[1] = trim($stop1[1]);
			if (strpos($stop1[1],'nż') !== false) {
				$nz=1;
				$stop1[1] = str_replace(" – nż","",$stop1[1]);
				$stop1[1] = str_replace(" - nż","",$stop1[1]);
			} else {
				$nz=0;
			}
			if ($i != count($stops1) - 1)
				$sql .= "(NULL,'$stop','$stop1[0]','$stop1[1]','',$nz),";
			else
				$sql .= "(NULL,'$stop','$stop1[0]','$stop1[1]','',$nz)";
		}
		$i++;
	}

	$sql .= ";";

	return $sql;
}
?>