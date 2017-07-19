<?php
namespace models;

use libs\Application\Container;
use libs\Traits\Di;

abstract class Base
{
	use Di;

	/** @var  \mysqli */
	protected $connection;

	public function __construct(\mysqli $connection, Container $di)
	{
		$this->connection   = $connection;
		$this->di           = $di;
	}
}