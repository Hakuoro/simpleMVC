<?php
/**
 * Created by PhpStorm.
 * User: Boris
 * Date: 09.07.2017
 * Time: 14:33
 */

namespace libs\Application;


abstract class Response
{
	protected $body = '';
	protected $headers = [];
	protected $status;


	protected function sendHeaders()
	{
		if (headers_sent()) {
			return;
		}
		if ($this->headers) {
			foreach ($this->headers as $name => $value) {
				$name = str_replace(' ', '-', ucwords(str_replace('-', ' ', $name)));
				header("$name: $value", true);
			}
		}
	}

	public function setBody($body)
	{
		$this->body = $body;
		return $this;
	}

	public function setStatus($status)
	{
		$this->status = $status;
		return $this;
	}

	protected function sendBody()
	{
		echo $this->body;
	}

	public function addHeader ($headerName, $value)
	{
		$this->headers[$headerName] = $value;
	}

	public function removeHeader ($headerName)
	{
		if (!empty($this->headers[$headerName])) {
			unset($this->headers[$headerName]);
		}
	}

	public function send()
	{
		$this->sendHeaders();
		$this->sendBody();
	}
}