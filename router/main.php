<?php

$app->hook('slim.before.dispatch', function() use ($view,$lang,$app) {
	$langs = ["en","pl"];
    // $styles = ["1","2","3"];
    $styles = json_decode('[ { "number": "1", "name": "indeksowy", "img": "style_1.png" }, { "number": "2", "name": "poziomy", "img": "style_2.png" }, { "number": "3", "name": "pionowy", "img": "style_3.png" }, { "number": "4", "name": "przystankowy", "img": "style_4.png" } ]');
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
	$view->getInstance()->assign('lang',$lang);
	$view->getInstance()->assign('style',$app->style);
	$view->getInstance()->assign('languages',$langs);
	$view->getInstance()->assign('styles',$styles);
});

$app->get('/', function() use ($app) {
    $app->render('main/index.tpl');
});