<?php

$app->group('/line', function() use ($app) {
    
    $app->get('/', function() use ($app) {
        $app->redirect('../');
    });
    
    $app->get('/:line', function($line) use ($app) {
        $data = array();
        $directions = get_line_directions($line);
        
        foreach ($directions as $direction) {
            $temp = array(
                "name"  => $direction->name,
                "stops" => array()
            );
            $temp["stops"] = get_line_route($line,$direction->dirnumber);
            $data[] = $temp;
        }
        $app->render('directions.tpl',array("line"=>$line,"data"=>$data));
    });
    
    
});
