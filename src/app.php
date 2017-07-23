<?php

use Silex\Application;
use Silex\Provider\MonologServiceProvider;

use Ace\Provider\StoreProvider;
use Ace\Provider\RouteProvider;
use Ace\Provider\ErrorHandlerProvider;
use Ace\Provider\ConfigurationProvider;

require_once __DIR__ . '/vendor/autoload.php';

$app = new Application();

$app->register(new MonologServiceProvider());
$app['monolog.logfile'] = "php://stdout";
$app['monolog.name'] = 'roles';

$app->register(new ConfigurationProvider());
$app->register(new ErrorHandlerProvider());
$app->register(new StoreProvider());
$app->register(new RouteProvider());

// debug env vars during development
/*
$app['logger']->info("RDS_HOSTNAME = " . getenv('RDS_HOSTNAME'));
$app['logger']->info("RDS_PORT = " . getenv('RDS_PORT'));
$app['logger']->info("RDS_DB_NAME = " . getenv('RDS_DB_NAME'));
$app['logger']->info("RDS_USERNAME = " . getenv('RDS_USERNAME'));
$app['logger']->info("RDS_PASSWORD = " . getenv('RDS_PASSWORD'));
*/

return $app;
