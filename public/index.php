<?php

require_once '../init.php';

$config = new \libs\Application\Config('prod');

if (ENVIRONMENT !== 'prod') {
	$config->merge(ENVIRONMENT);
}

// routes config
$config->merge('routes');

$request = new \libs\Application\Request();

$di = new \libs\Application\Container($config);

$di->set('mysql', function () use ($config) {
	$dns = $config->get('mysql');
	$mysqli = new mysqli($dns['host'], $dns['user'] , $dns['pass'], $dns['db']);
	if ($mysqli->connect_errno) {
		throw new Exception("Не удалось подключиться к MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
	}
	return $mysqli;
});

$app = new \libs\Application\App($config, $request, $di);

$app->run();