<?php

require_once __DIR__ . '/../vendor/autoload.php';

\Ruesin\Utils\Config::load(__DIR__ . '/config/redis.php');
\Ruesin\Utils\Config::loadFile(__DIR__ . '/config/server.php');

\Ruesin\Utils\Config::set('server.unique_name', 'unique_file');

$server = \Ruesin\Utils\Config::get('server');
$redis  = \Ruesin\Utils\Config::get('redis');

echo '<pre>';
var_dump($server);
var_dump($redis);
die();
