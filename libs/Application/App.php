<?php

namespace libs\Application;

use controllers\Base;

class App
{
	/** @var Config  */
	private $config;

	/** @var Request  */
	private $request;

	/** @var  Container */
	private $di;

	public function __construct(Config $config, Request $request, Container $di)
	{
		$this->config   = $config;
		$this->request  = $request;
		$this->di       = $di;
	}

	public function run ()
	{
		try {
			$router = new Router($this->config->get(Router::CONFIG_FIELD));

			list ($controllerName, $actionName) = $router->match($this->request);

			/** @var Base $controller */
			$controller = new $controllerName($this->request, $this->di);

			if (!method_exists($controller, $actionName)) {
				throw  new \Exception('Action doesnt exists: ' . $actionName);
			}

			/** @var Response $response */
			$response = $controller->$actionName();

		} catch (\Exception $e) {

			if (ENVIRONMENT == 'dev') {
				print_r($e->getMessage());
			}
			error_log($e->getMessage());
			//todo redirect to error page

			exit;
		}

		$response->send();
	}
}