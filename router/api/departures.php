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

$app->get('/departures/:line/:direction/:stop', function($line,$direction,$stop) use($app) {
    $departures = get_departures($line, $direction, $stop);
    $stop_name = get_stop_name($stop);
    $departures["stop_name"] = array(
        "id" => $stop_name->id,
        "name1" => $stop_name->name1,
        "name2" => $stop_name->name2,
        "req" => $stop_name->req
    );
    echo json_encode($departures);
});