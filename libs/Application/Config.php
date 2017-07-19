<?php
/**
 * Created by PhpStorm.
 * User: Boris
 * Date: 08.07.2017
 * Time: 22:23
 */

namespace libs\Application;


class Config
{

	public $config;

	public function __construct($configFileName = null)
	{
		$config = $this->getFullFileName($configFileName);
		$this->config = $this->loadConfig($config);
	}

	protected function getFullFileName ($configFileName) {
		return BASE_PATH . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . $configFileName . '.php';
	}

	protected function loadConfig ($file)
	{
		if (!file_exists($file)) {
			throw new \Exception('Unknown file: ' . $file);
		}

		return include $file;
	}

	public function merge($configFileName)
	{
		$config = $this->getFullFileName($configFileName);
		$this->config = array_replace_recursive($this->config, $this->loadConfig($config));
	}

	public function get($field)
	{
		if (!isset($this->config[$field])) {
			throw new \Exception('Unknown config name: '.$field);
		}

		return $this->config[$field];
	}
}