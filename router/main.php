<?php

$app->hook('slim.before.dispatch', function() use ($view) {
	$current_time = time();
	$next_hour_time = $current_time + 3600;
    $lines = R::findAll('lines', 'ORDER BY line*1');
	if(count($lines) > 0) {
		$view->getInstance()->assign('lines',$lines);
	} else {
		$view->getInstance()->assign('lines',array());		
	}
	$view->getInstance()->assign('current_time',$current_time);
	$view->getInstance()->assign('next_hour_time',$next_hour_time);
});

$app->get('/', function() use ($app) {
    $app->render('main/index.tpl');
});