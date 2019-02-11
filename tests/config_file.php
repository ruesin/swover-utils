<?php

require_once __DIR__ . '/../vendor/autoload.php';

$config = \Ruesin\Utils\Config::load(__DIR__ . '/config/redis.php');
$config2 = \Ruesin\Utils\Config::loadFile(__DIR__ . '/config/server.php');

var_dump($config == $config2);
echo PHP_EOL;

\Ruesin\Utils\Config::set('server.unique_name', 'unique_file');

$server = $config->get('server');
$redis = \Ruesin\Utils\Config::get('redis');

echo '<pre>';
var_dump($server);
var_dump($redis);
die();
