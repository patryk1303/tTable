<?php

session_start();

require_once 'lib/Slim/Slim.php';

\Slim\Slim::registerAutoloader();

require_once 'lib/Smarty/Smarty.class.php';
require_once 'lib/Slim-Views/Smarty.php';
require_once 'lib/rb.php';
require_once 'config.php';
require_once 'lib/db_func.php';
require_once 'lib/departures.php';

// application configuration
$app = new \Slim\Slim(array(
    'view' => new \Slim\Views\Smarty(),
    'debug' => true,
    'templates.path' => './templates'
));
date_default_timezone_set(TIMEZONE);

$req = $app->getInstance()->request();
$app->baseUrl = $req->getUrl() . $req->getRootUri();
unset($req);

// templates engine configuration
$view = $app->view();
$view->parserDirectory = dirname(__FILE__) . '/templates';
$view->parserCompileDirectory = $view->parserDirectory . '/compile';
$view->parserCacheDirectory = $view->parserDirectory . '/cache';
$view->parserExtensions = array(
    dirname(__FILE__) . '/lib/Slim-Views/SmartyPlugins'
);

// database connection configuration
switch(DB_TYPE) {
    case 'sqlite':
        $file_path = dirname(__FILE__).'/db/'.DB_FILE;
//        echo $file_path;
        if(!file_exists($file_path)) {
            touch($file_path);
        }
        R::setup('sqlite:'.$file_path);
        break;
    case 'mysql':
    default:
        R::setup('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
        break;
}

require_once 'router/Init.php';

$app->run();