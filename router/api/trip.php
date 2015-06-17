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

$app->get('/trip/:line/:dir_no/:stop_id/:daytype_id/:trip_no', function($line,$dir_no,$stop_id,$daytype_id,$trip_no) use($app) {
    $trip = get_trip($line,$dir_no,$daytype_id,$trip_no);
    
    $app->render('api/trip.tpl', array("trip"=>$trip,"stop_id"=>$stop_id,"line"=>$line,"dir_no"=>$dir_no));
    
//    echo json_encode($trip);
});