<?php

$app->group('/line', function() use ($app) {
    
    $app->get('/', function() use ($app) {
        $app->redirect('../');
    });
    
    $app->get('/:line', function($line) use ($app) {
       echo "line = $line"; 
    });
    
});
