<?php
/**
 * Created by PhpStorm.
 * User: Boris
 * Date: 09.07.2017
 * Time: 13:37
 */

namespace libs\Application;


class Router
{
	const CONFIG_FIELD = 'routes';

	const DEFAULT_CONTROLLER    = 'task';
	const DEFAULT_ACTION        = 'list';

	/** @var array  */
	protected $routes = [];

	public function __construct(array $routes)
	{
		$this->routes = $routes;
	}

	public function match(Request $request)
	{
		if (isset($this->routes[$request->getPatch()])) {
			list ($controllerName, $actionName) = [$this->routes[$request->getPatch()]['controller'], $this->routes[$request->getPatch()]['action']];
		} else {
			list ($controllerName, $actionName) = explode('/', ltrim($request->getPatch(),'/'));
		}

		if (!$controllerName) {
			$controllerName = self::DEFAULT_CONTROLLER;
		}

		if (!$actionName) {
			$actionName     = self::DEFAULT_ACTION;
		}

		return ['controllers\\'.ucfirst($controllerName), $actionName. 'Action'];
	}

}