<?php

/* 
 * Copyright 2015 Patryk.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

//{siteUrl url='/trip/'}{$line}/{$dir_no}/{$stop_id}/{$departure_day.daytype_number}/{$minute.tripnumber}

$app->get('/lines', function() use($app) {
    $lines = R::getAll('SELECT line FROM `lines` ORDER BY line*1');
    echo json_encode($lines);
});

$app->get('/line/:line', function($line) {
 	$data = array();
    $directions = get_line_directions($line);
    foreach($directions as $direction) {
    	$temp = array(
    		"name" => $direction->name,
    		"stops" => get_line_route($line,$direction->dirnumber)
    	);
    	$data[] = $temp;
    }

    echo json_encode($data);
});