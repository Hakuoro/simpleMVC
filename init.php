<?php

define('BASE_PATH', realpath(dirname(__FILE__)));
define('ENVIRONMENT', 'dev');

spl_autoload_register(function ($class) {
	$filename = BASE_PATH . DIRECTORY_SEPARATOR . str_replace('\\', '/', $class) . '.php';
	if (!file_exists($filename)) {
		throw new Exception('Unknown file: ' . $filename);
	}
	require_once ($filename);
});