<?php

$app->group('/departures', function() use ($app) {
    
    $app->get('/', function() use ($app) {
        $app->redirect('../');
    });
    
    $app->get('/:line/:direction/:stop', function($line,$direction,$stop) use ($app) {
        $data = array(
            "route" => get_line_route($line, $direction),
            "departures" => array()
        );
        $dir_name = R::findOne("directions", " line = :line AND dirnumber = :dir_no",
            array(
                ":line" => $line,
                ":dir_no" => $direction
            ))->name;
        $daytypes = R::find("daytypes");
        $hours = get_departures_hours($line, $direction, $stop);
        
        foreach($daytypes as $daytype) {
            $temp = array(
                "daytype" => $daytype->name,
                "daytype_number" => $daytype->id,
                "count" => 0,
                "departures" => array()
            );
            foreach($hours as $hour) {
                $departures = get_departures_for_hour($line, $direction, $stop, $daytype->id, $hour);
                if(count($departures)) {
                    $temp["count"]++;
                }
                $temp["departures"][] = array(
                    "hour" => $hour,
                    "minutes" => $departures
                );
            }
            $data["departures"][] = $temp;
        }
        
        $app->render("departures.tpl",array(
            "line" => $line,
            "dir_name" => $dir_name,
            "dir_no" => $direction,
            "stop_id" => $stop,
            "stop_name" => get_stop_name($stop),
            "signs" => get_signs($line, $direction, $stop),
            "route" => $data["route"],
            "departures" => $data["departures"]
        ));
    });
    
    $app->get('/:param+', function() use ($app) {
        $app->redirect('../');
    });
    
});
