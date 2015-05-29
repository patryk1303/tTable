<?php

function getLines() {
    $lines = array();
    $linesDir = glob('timetable/*', GLOB_ONLYDIR);
    foreach($linesDir as $line) {
        $directory = explode('/',$line);
        $lines[] = $directory[count($directory)-1];
    }
    sort($lines,SORT_NUMERIC);
    return $lines;
}

$app->group('/install', function() use ($app) {
    $app->get('/', function() use ($app) {
        $app->render('install/index.tpl');
    }); 
    $app->get('/daytypes', function() use ($app) {
        $daytypes = file("timetable/typy_dni");
        R::wipe("daytypes");
        foreach($daytypes as $daytype) {
            $day = R::dispense("daytypes");
            $day->name = $daytype;
            R::store($day);
        }
        $app->render('install/daytypes.tpl');
    }); 
    $app->get('/lines', function() use ($app) {
        $lines = getLines();
        R::wipe('lines');
        R::wipe('directions');
        foreach($lines as $line) {
            $lineDB = R::dispense('lines');
            $directionsFile = file("timetable/$line/kierunki");
            $directionNo = 1;
            foreach($directionsFile as $direction) {
                $directions = R::dispense('directions');
                $directions->name = $direction;
                $directions->dirnumber = $directionNo++;
                $lineDB->ownDirectionsList[] = $directions;
            }
            $lineDB->line = $line;
            $lineDB->date = file_get_contents("timetable/$line/data");
            R::store($lineDB);
        }
        $app->render('install/lines.tpl');
    });
    $app->get('/stops', function() use ($app) {
        
    }); 
    $app->get('/signs', function() use ($app) {
        
    }); 
    $app->get('/routes', function() use ($app) {
        
    }); 
    $app->get('/departures', function() use ($app) {
        
    }); 
    $app->get('/end', function() use ($app) {
        
    });
    
    $app->group('/api', function() use ($app) {
        $app->get('/daytypes', function() {
            $daytypes = R::findAll('daytypes');
            $out = array();
            foreach($daytypes as $daytype) {
                $out[] = array(
                    "id"    =>  $daytype->id,
                    "name"  =>  $daytype->name
                );
            }
            echo json_encode($out);
        });
        $app->get('/lines', function() {
            $out = array();
            $lines = R::findAll('lines');
            foreach($lines as $line) {
                $directions = R::find('directions', 'lines_id = :line_id', array('line_id' => $line->id));
                $arr = array();
                foreach($directions as $direction) {
                    $arr[] = array(
                        "dirNo" => $direction->dirnumber,
                        "dirName" => $direction->name
                    );
                }
                $out[] = array(
                    "line" => $line->line,
                    "directions" => $arr
                );
            }
            echo json_encode($out);
        });
    });
});