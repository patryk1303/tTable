<?php

$app->group('/json', function() use ($app) {
	// TODO move routing GET,POST to seperate files

	$app->get('/', function() use($app) {
		$app->redirect('./..');
	});

	$app->get('/lines', function() {
		$out = array();

		$lines = R::getAll("SELECT * FROM `lines`");
		foreach($lines as $line) {
			$directions = get_line_directions($line["line"]);
			$line["directions"] = array();

			foreach($directions as $direction) {
				$tmp = array();
				$tmp["dirno"] = $direction->dirnumber;
				$tmp["dirname"] = $direction->name;
				$tmp["route"] = get_line_route_wo_stops_names($line["line"],$direction->dirnumber);
				$line["directions"][] = $tmp;
			}
			$out[] = $line;
		}

		echo json_encode($out);
	});

	$app->get('/departures', function() {
		$out = array();
		
		$lines = R::getAll("SELECT * FROM `lines`");
		foreach($lines as $line) {
			$directions = get_line_directions($line["line"]);
			$line["directions"] = array();

			foreach($directions as $direction) {
				$tmp = array();
				$tmp["dirno"] = $direction->dirnumber;
				$tmp["dirname"] = $direction->name;
				$tmp["route"] = get_line_route_wo_stops_names($line["line"],$direction->dirnumber);
				$line["directions"][] = $tmp;
			}
			$out[] = $line;
		}

		echo json_encode($out);
	});

	$app->get('/stops', function() {
		echo json_encode(R::getAll("SELECT stops.* FROM stops"));
	});

});