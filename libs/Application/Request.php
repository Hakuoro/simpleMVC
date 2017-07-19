<?php
/**
 * Created by PhpStorm.
 * User: Boris
 * Date: 08.07.2017
 * Time: 22:37
 */

namespace libs\Application;


class Request
{
	protected $path;

	public function __construct()
	{
		list($this->path) = explode('?',$this->getServer('REQUEST_URI'));
	}


	public function getFile($name)
	{
		if (isset($_FILES[$name])) {
			return $_FILES[$name];
		}

		return null;
	}

	public function get($name, $default = null)
	{
		if (isset($_REQUEST[$name])) {
			return $_REQUEST[$name];
		}

		return $default;
	}

	public function getPatch()
	{
		return $this->path;
	}

	public function getServer($key, $default = null)
	{
		return isset($_SERVER[$key]) ? $_SERVER[$key] : $default;
	}

	public function isHttps()
	{
		return $this->getServer('HTTP_X_FORWARDED_HTTPS') || $this->getServer('HTTP_X_FORWARDED_PROTO')=='https';
	}

	public function getMethod()
	{
		return $this->getServer('REQUEST_METHOD');
	}

	public function isPost()
	{
		return ($this->getMethod()=='POST');
	}

	public function isAjax()
	{
		return $this->getServer("HTTP_X_REQUESTED_WITH") == "XMLHttpRequest";
	}

	public function getReferer()
	{
		return $this->getServer('HTTP_REFERER', '');
	}
}