<?php

$polish_letters = array("ą","ę","ł","ó","ś","ć","ź","ż","ń","Ą","Ę","Ł","Ó","Ś","Ć","Ź","Ż","Ń");

/**
 * 
 * @return type
 */
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

/**
 * 
 * @return type
 */
function getStops() {
    $lines = getLines();
    $stops = array();
    $stops1 = array();
    foreach($lines as $line) {
        $directions_count = count(file("timetable/$line/kierunki"));
        for($i=1;$i<=$directions_count;$i++) {
            $stops_file = file("timetable/$line/przystanki_$i");
            foreach($stops_file as $stop_row) {
                $stops[] = $stop_row;
            }
        }
    }
    
    foreach($stops as $stop) {
        $stops1[] = trim($stop);
    }
    
    $stops1 = array_unique($stops1);
    sort($stops1);
    
    return $stops1;
}

/**
 * 
 * @return type
 */
function getSigns() {
    $lines = getLines();
    $signs = array();
    foreach($lines as $line) {
        $lineDB = R::findOne('lines', ' line = :line', array(':line' => $line));
        $directions_count = count($lineDB->ownDirectionsList);
        $i=1;
        foreach($lineDB->ownDirectionsList as $direction) {
            $signs_file = file("timetable/$line/legenda_$i");
            foreach($signs_file as $sign) {
                $sign = explode(' - ', $sign);
                $signs[] = array(
                    "line_id"   =>  $direction->lines_id,
                    "dir_id"    =>  $direction->id,
                    "sign"      =>  $sign[0],
                    "desc"      =>  $sign[1]
                );
            }
            $i++;
        }
    }
    return $signs;
}

/**
 * 
 * @param type $name1
 * @param type $name2
 * @return type
 */
function getStopID($name1,$name2) {
    $id = R::findOne("stops", "name1 LIKE :name1 AND name2 LIKE :name2", array(
        ":name1" => $name1,
        ":name2" => $name2
    ));
    
    return isset($id->id)?$id->id:-1;
}

/**
 * 
 * @param type $full_stop_name
 */
function getStopID1($full_stop_name) {
    global $polish_letters;
    $stop = explode("/", $full_stop_name);
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
    return $stop_id;
}

/**
 * 
 * @param type $array
 */
function transpose($array) {
    array_unshift($array, null);
    return call_user_func_array('array_map', $array);
}