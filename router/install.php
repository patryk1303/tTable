<?php

$polish_letters = array("ą","ę","ł","ó","ś","ć","ź","ż","ń","Ą","Ę","Ł","Ó","Ś","Ć","Ź","Ż","Ń");
require_once 'lib/install.php';

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
    $app->get('/stops', function() use ($app) {
        $stops = getStops();
        R::wipe('stops');
        
        foreach($stops as $stop) {
            $stopDB = R::dispense('stops');
            $stop = trim($stop);
            $stop = explode('/',$stop);
            $count = count($stop);
            if($count == 1) { // has no name2
                $index = 0;
            } elseif($count == 2) { // has name2
                $index = 1;
            }
            
            $stop[0] = trim($stop[0]);
            $stop[1] = $count == 2 ? trim($stop[1]) : '';
            if(strpos($stop[$index],'nż') !== false) {
                $is_req = 1;
                $stop[$index] = str_replace(" – nż","",$stop[$index]);
                $stop[$index] = str_replace(" - nż","",$stop[$index]);
            } else {
                $is_req = 0;
            }
            $stopDB->name1 = $stop[0];
            $stopDB->name2 = $stop[1];
            $stopDB->req = $is_req;
            
            R::store($stopDB);
        }
        
        $app->render('install/stops.tpl');
    });
    $app->get('/lines', function() use ($app) {
        global $polish_letters;
        $lines = getLines();
        R::wipe('lines');
        R::wipe('directions');
        foreach($lines as $line) {
            $line_dir = "timetable/$line";
            $lineDB = R::dispense('lines');
            $directionsFile = file("$line_dir/kierunki");
            $directionNo = 1;
            foreach($directionsFile as $direction) {
                $directions = R::dispense('directions');
                $directions->name = $direction;
                $directions->dirnumber = $directionNo;
                $lineDB->ownDirectionsList[] = $directions;
                
                // signs
                $signs_file = file("$line_dir/legenda_$directionNo");
                foreach($signs_file as $sign) {
                    $signs = R::dispense('signs');
                    $sign = explode(' - ', $sign);
                    $signs->sign = $sign[0];
                    $signs->description = $sign[1];
                    $signs->dirnumber = $directionNo;
                    $lineDB->ownSignsList[] = $signs;
                }
                
                // routes
                $stops_file = file("$line_dir/przystanki_$directionNo");
                $stops_ids = array();
                foreach($stops_file as $stop) {
                    $routeDB = R::dispense('routes');
                    $stop = explode("/",$stop);
                    $stop = array_map("trim",$stop);
                    $stop[0] = str_replace(" - nż","",$stop[0]);
                    $stop[0] = str_replace(" – nż","",$stop[0]);
                    $stop[0] = str_replace($polish_letters,"%",$stop[0]);
                    if(count($stop)==2) {
                        $stop[1] = str_replace(" - nż","",$stop[1]);
                        $stop[1] = str_replace(" – nż","",$stop[1]);
                        $stop[1] = str_replace($polish_letters,"%",$stop[1]);
                        $stop_id = getStopID($stop[0], $stop[1]);
                    } else {
                        $stop_id = getStopID($stop[0], "");
                    }
                    $routeDB->stopid = $stop_id;
                    $routeDB->dirnumber = $directionNo;
                    $lineDB->ownRoutesList[] = $routeDB;
                }
                
                $directionNo++;
            }
            $lineDB->line = $line;
            $lineDB->date = file_get_contents("timetable/$line/data");
            R::store($lineDB);
        }
        $app->render('install/lines.tpl');
    });
//    $app->get('/routes', function() use ($app) {
//        $lines = getLines();
//        R::wipe('routes');
//        foreach($lines as $line) {
//            $lineID = R::findOne('lines',"line = :line",array(":line"=>$line))->id;
//            echo "$line - $lineID <br>";
//            $directionsFile = file("timetable/$line/kierunki");
//            $directionNo = 1;
//            foreach($directionsFile as $direction) {
//                
//                $directionNo++;
//            }
//        }
//    }); 
    $app->get('/departures', function() use ($app) {
        
    }); 
    $app->get('/end', function() use ($app) {
        
    });
    
    $app->group('/api', function() use ($app) {
        $app->group('/install', function() use ($app) {
            
        });
        $app->post('/check-connection', function() use ($app) {
            $data = $app->request->post();
        });
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
        $app->get('/stops',function() {
            $out = array();
            $stops = R::findAll('stops');
            foreach($stops as $stop) {
                $out[] = array(
                    "id"   => $stop->id,
                    "name" => $stop->name1 . ' / ' . $stop->name2,
                    "req"  => $stop->req
                );
            }
            echo json_encode($out);
        });
    });
});