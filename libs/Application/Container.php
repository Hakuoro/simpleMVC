<?php

namespace libs\Application;


class Container
{
	/** @var array  */
	private $objects = [];

	/** @var  Config */
	private $config;

	public function __construct(Config $config)
	{
		$this->config = $config;
	}


	public function set($name, $value)
	{
		$this->objects[$name] = $value;
	}

	public function get($name)
	{
		if (!isset($this->objects[$name])) {
			throw new \Exception('Object doesnt exists: ' . $name);
		}
		if (is_callable($this->objects[$name])) {
			$cb = $this->objects[$name];
			$this->objects[$name] = $cb();
		}

		return $this->objects[$name];
	}
}