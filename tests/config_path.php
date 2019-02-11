<?php

require_once __DIR__ . '/../vendor/autoload.php';

$config = \Ruesin\Utils\Config::load(__DIR__ . '/config/');

\Ruesin\Utils\Config::set('server.unique_name', 'unique');

$server = \Ruesin\Utils\Config::get('server');
$redis  = \Ruesin\Utils\Config::get('redis');

echo '<pre>';
var_dump($server);
var_dump($redis);
die();
