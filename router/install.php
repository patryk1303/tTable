<?php
require_once 'lib/install.php';

$app->group('/install', function() use ($app) {
    $app->get('/', function() use ($app) {
        $app->render('install/index.tpl');
    });
    $app->get('/config', function() use ($app) {
        $app->render('install/config.tpl');
    });
    $app->map('/daytypes', function() use ($app) {
        $daytypes = file("timetable/typy_dni");
        R::wipe("daytypes");
        foreach($daytypes as $daytype) {
            $day = R::dispense("daytypes");
            $day->name = $daytype;
            R::store($day);
        }
        $app->render('install/daytypes.tpl');
    })->via("GET","POST");
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
        $lines = getLines();
        R::wipe('lines');
        R::wipe('directions');
//        R::wipe('departures');
        R::wipe('routes');
        R::wipe('signs');
        
        R::exec("DROP TABLE IF EXISTS `departures`");
        R::exec("CREATE TABLE IF NOT EXISTS `departures` ( `id` int(11) unsigned NOT NULL, `daytype` int(11) unsigned DEFAULT NULL, `stopid` int(11) unsigned DEFAULT NULL, `dirnumber` int(11) unsigned DEFAULT NULL, `tripnumber` int(11) unsigned DEFAULT NULL, `hour` int(11) unsigned DEFAULT NULL, `min` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL, `signs` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL, `line` int(11) unsigned DEFAULT NULL ) ENGINE=InnoDB DEFAULT CHARSET=utf8");
        R::exec("ALTER TABLE `departures` ADD PRIMARY KEY (`id`)");
        R::exec("ALTER TABLE `departures` MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT");
        
        $app->render('install/lines_1.tpl',array("lines"=>$lines));
    });
    $app->get('/end', function() use ($app) {
        $app->render('install/end.tpl');
    });
    
    // API for AJAX requests
    $app->group('/api', function() use ($app) {
        // install requests
        $app->group('/install', function() use ($app) {
            $app->post('/line/:line', function($line) use ($app) {
                $line_dir = "timetable/$line";
                
                if(!file_exists($line_dir)) {
                    echo "Linia nie istnieje";
                    return;
                }
                
                $lineDB = R::dispense('lines');
                $lineDB->line = $line;
                $lineDB->date = file_get_contents("$line_dir/data");
                
                R::store($lineDB);
                
                echo $line;
            });
            $app->post('/directions/:line', function($line) use ($app) {
                $line_dir = "timetable/$line";
                
                if(!file_exists($line_dir)) {
                    echo "Linia nie istnieje";
                    return;
                }
                
                $directionsFile = file("$line_dir/kierunki");
                $directionNo = 1;
                foreach($directionsFile as $direction) {
                    $directionsDB = R::dispense('directions');
                    
                    $directionsDB->name = $direction;
                    $directionsDB->dirnumber = $directionNo++;
                    $directionsDB->line = $line;
                    
                    R::store($directionsDB);
                }
                
                echo $line;
            });
            $app->post('/signs/:line', function($line) use ($app) {
                $line_dir = "timetable/$line";
                
                if(!file_exists($line_dir)) {
                    echo "Linia nie istnieje";
                    return;
                }
                
                $directionsCount = count(file("$line_dir/kierunki"));
                for($directionNo=1 ; $directionNo<=$directionsCount ; $directionNo++) {
                    $signs_file = file("$line_dir/legenda_$directionNo");
                    foreach($signs_file as $sign) {
                        $signsDB = R::dispense('signs');
                        $sign = explode(' - ', $sign);
                        
                        $signsDB->sign = $sign[0];
                        $signsDB->description = $sign[1];
                        $signsDB->dirnumber = $directionNo;
                        $signsDB->line = $line;
                        
                        R::store($signsDB);
                    }
                }
                
                echo $line;
            });
            $app->map('/routes/:line', function($line) use ($app) {
                $line_dir = "timetable/$line";
                
                if(!file_exists($line_dir)) {
                    echo "Linia nie istnieje";
                    return;
                }
                
                $directionsCount = count(file("$line_dir/kierunki"));
                for($directionNo=1 ; $directionNo<=$directionsCount ; $directionNo++) {
                    $stops_file = file("$line_dir/przystanki_$directionNo");
                    $stops_ids = array();
                    
                    foreach($stops_file as $stop) {
                        $routeDB = R::dispense('routes');
                        $stop_id = getStopID1($stop);
                        
                        $routeDB->stopid = $stop_id;
                        $routeDB->dirnuber = $directionNo;
                        $routeDB->line = $line;
                        
                        R::store($routeDB);
                    }
                }
                
                echo $line;
            })->via('POST','GET');
            $app->map('/departures/:line', function($line) use ($app) {
                $all_departures = array();
                $line_dir = "timetable/$line";
                
                if(!file_exists($line_dir)) {
                    echo "Linia nie istnieje";
                    return;
                }
                
                $daytypes = R::findAll('daytypes');
                $min_regex = "/([0-9][0-9])([a-zA-Z]*)/";
                $directionsCount = count(file("$line_dir/kierunki"));
                for($directionNo=1 ; $directionNo<=$directionsCount ; $directionNo++) {
                    for($j=1;$j<=count($daytypes);++$j) {
                        $departures = array();
                        $departures_file_path = "$line_dir/$directionNo"."_$j";
                        if(file_exists($departures_file_path)) {
                            $departures_file = file($departures_file_path);
                            $stops_file = file("$line_dir/przystanki_$directionNo");
                            foreach($departures_file as $departures_row) {
                                    $departures_row = preg_replace('~[\r\n]+~','', $departures_row);
                                    $departures[] = explode("\t",$departures_row);
                            }
                            $departures = transpose($departures);

                            $trip_number = 1;
                            foreach($departures as $departures_row) {
                                $stop_number = 0;
                                foreach($departures_row as $departure) {
                                    //when departure exists
                                    if(strlen($departure) > 2) {
                                            $departure1 = explode(":", $departure);
                                            preg_match($min_regex,$departure1[1],$min);
                                            $all_departures[] = array(
                                                "daytype" => $daytypes[$j]->id,
                                                "stopid" => getStopID1($stops_file[$stop_number]),
                                                "dirno" => $directionNo,
                                                "tripno" => $trip_number,
                                                "hour" => $departure1[0],
                                                "min" => $min[1],
                                                "signs" => $min[2],
                                                "line" => $line
                                            );
                                    }
                                    $stop_number++;
                                }
                                $trip_number++;
                            }
                        }
                    }
                }
                
                $sql = "INSERT INTO `ttable`.`departures` (`id`, `daytype`, `stopid`, `dirnumber`, `tripnumber`, `hour`, `min`, `signs`, `line`) VALUES ";
                foreach($all_departures as $departure) {
                    $daytype = $departure['daytype'];
                    $stopid = $departure['stopid'];
                    $dirno = $departure['dirno'];
                    $tripno= $departure['tripno'];
                    $hour= $departure['hour'];
                    $min= $departure['min'];
                    $signs= $departure['signs'];
                    $line= $departure['line'];
                    $sql .= "(NULL, '$daytype', '$stopid', '$dirno', '$tripno', '$hour', '$min', '$signs', '$line'), ";
                    //TODO use pure SQL statement to add departures to DMBS
                }
                $sql .= ";";
                $sql = str_replace(", ;", ";", $sql);
                
                R::exec($sql);
                
                echo $line;
            })->via('POST','GET');
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