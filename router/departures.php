<?php

$app->group('/departures', function() use ($app) {
    
    $app->get('/', function() use ($app) {
        $app->redirect('../');
    });
    
    $app->get('/:line/:direction/:stop', function($line,$direction,$stop) use ($app) {        
        $departures = get_departures($line, $direction, $stop);

        $app->render("departures.tpl",array(
            "line" => $line,
            "dir_name" => $departures["direction_name"],
            "dir_no" => $direction,
            "stop_id" => $stop,
            "stop_name" => get_stop_name($stop),
            "signs" => $departures["signs"],
            "route" => $departures["route"],
            "departures" => $departures["departures"],
            "other_lines" => $departures["other_lines"],
            "line_date" => $departures["line_date"]
        ));
    });
    
    $app->get('/:param+', function() use ($app) {
        $app->redirect('../');
    });
    
});
