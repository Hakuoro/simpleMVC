<?php
namespace controllers;


use libs\Application\Container;
use libs\Application\Request;
use libs\Traits\Di;

abstract class Base
{
	use Di;

	/** @var  Request */
	protected $request;

	public function __construct(Request $request, Container $di)
	{
		$this->request  = $request;
		$this->di       = $di;
	}
}