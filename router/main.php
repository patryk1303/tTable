<?php

$app->hook('slim.before.dispatch', function() use ($view) {
    $lines = R::findAll('lines', 'ORDER BY line*1');
	if(count($lines) > 0) {
		$view->getInstance()->assign('lines',$lines);
	} else {
		$view->getInstance()->assign('lines',array());		
	}
});

$app->get('/', function() use ($app) {
    $app->render('main/index.tpl');
});