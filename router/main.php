<?php

$app->hook('slim.before.dispatch', function() use ($view) {
    $lines = R::findAll('lines', 'ORDER BY line');
    $view->getInstance()->assign('lines',$lines);
});

$app->get('/', function() use ($app) {
    $app->render('main/index.tpl');
});