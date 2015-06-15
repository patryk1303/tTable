<?php

function get_stop_name($stop_id) {
    return R::load('stops', $stop_id);
}

function get_line_route($line,$dir_number) {
    return R::getAll("Select routes.*, stops.* From routes, stops Where routes.stopid = stops.id AND routes.line = :line AND routes.dirnuber = :dirno Order By routes.line, routes.dirnuber, routes.id",
        array(
            ":line" => $line,
            ":dirno" => $dir_number
        )
    );
}

function get_departures_hours($line,$dir_number,$stop_id) {
    $ret = array();
    $hours =  R::getAll("Select Distinct departures.hour From departures Where departures.line = :line And departures.dirnumber = :dir_number And departures.stopid = :stop_id",
        array(
            ":line" => $line,
            ":dir_number" => $dir_number,
            ":stop_id" => $stop_id
        )
    );
    foreach($hours as $hour) {
        $ret[] = $hour["hour"];
    }
    return $ret;
}

function get_departures_for_hour($line,$dir_number,$stop_id,$daytype_id,$hour) {
    return R::getAll("Select Distinct departures.min, departures.signs, departures.tripnumber From departures Where departures.hour = :hour And departures.line = :line And departures.dirnumber = :dir_number And departures.stopid = :stop_id And departures.daytype = :daytype",
        array(
            ":hour" => $hour,
            ":line" => $line,
            ":dir_number" => $dir_number,
            ":stop_id" => $stop_id,
            ":daytype" => $daytype_id,
            )
    );
}

function get_signs($line,$dir_number,$stop_id) {
    $current_signs = array();
    $current_stop_signs = array();
    $signs_line_dir_stop = R::getAll("Select Distinct departures.signs From departures Where departures.line = :line And departures.dirnumber = :dir_number And departures.stopid = :stop_id",
        array(
            ":line" => $line,
            ":dir_number" => $dir_number,
            ":stop_id" => $stop_id
        )
    );
    $signs_line_dir = R::getAll("Select Distinct signs.sign, signs.description From signs Where signs.line = :line And signs.dirnumber =  :dir_number",
        array(
            ":line" => $line,
            ":dir_number" => $dir_number
        )
    );
    
    foreach($signs_line_dir_stop as $row) {
        foreach(str_split($row["signs"]) as $sign) {
            if($sign != "") {
                $current_stop_signs[] = $sign;
            }
        }
    }
    
    $current_stop_signs = array_unique($current_stop_signs);
    
    foreach($signs_line_dir as $i=>$sign) {
        if(in_array($sign["sign"], $current_stop_signs)) {
            $current_signs[] = $sign;
        }
    }
    
    return $current_signs;
}