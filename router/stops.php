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

$app->group('/stops', function() use ($app) {
   $app->get('/', function() use($app) {
//       $stops = R::findAll('stops', 'ORDER BY name1,name2');
       $stops = get_stops_filter('');
       $lines_from_stops = array();
       foreach($stops as $stop) {
//           $lines_from_stops[] = R::getAll("Select Distinct Group_Concat(Distinct departures.line Order By departures.line*1 Separator ' ') As `lines` From departures Where departures.stopid = " . $stop["id"]);
           $lines_from_stops[] = get_lines_from_stop($stop["id"]);
       }
       
       $app->render('stops/stops.tpl', array("stops"=>$stops,"lines_stops"=>$lines_from_stops));
   });
   $app->get('/chrono/:id', function($id) use($app) {
       $departures = array();
       $daytypes = R::find('daytypes');
       foreach($daytypes as $daytype) {
           $temp = array(
               "daytype" => $daytype->name,
               "daytype_id" => $daytype->id,
               "departures" => get_stop_chrono_departures($id, $daytype->id)
           );
           $departures[] = $temp;
       }
       $app->render('stops/chrono_stop.tpl', array(
           "departures"=>$departures,
           "stop_id"=>$id,
           "stop_name"=>  get_stop_name($id)
       ));
   });
   $app->get('/all/:id', function($id) use($app) {
       $departures = array();
       $lines_and_dirs = get_lines_and_directions_no_from_stop($id);
       
       foreach($lines_and_dirs as $line) {
           $tmp = get_departures($line["line"], $line["dirnumber"], $id);
           $departures[] = $tmp;
       }
       
       $app->render('stops/all_stop.tpl', array(
           "departures" => $departures,
           "stop_id" => $id,
           "stop_name"=>  get_stop_name($id)
       ));
   });
});