<?php

namespace views;

use libs\Application\Container;
use libs\Traits\Di;

class Template
{
	use Di;

	/** @var string  */
	protected $template = '';

	/** @var array  */
	protected $data = [];

	/** @var array  */
	protected $placeholders = [];

	protected $basePath = BASE_PATH . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR ;

	public function __construct(Container $di, $data, $template = '')
	{
		$this->di = $di;
		if ($template) {
			$this->template = $template;
		}

		$this->data = $data;
	}


	public function setTemplate($template)
	{
		$this->template = $template;
		return $this;
	}

	public function setData($data)
	{
		$this->data = array_merge_recursive($this->data, $data);
		return $this;
	}

	public function set($name, $value)
	{
		$this->data[$name] = $value;
		return $this;
	}


	protected function parseTemplate ($template, $data)
	{
		if (preg_match_all('/{{(.*)}}/i', $template, $placeholders) === false) {
			throw new \Exception('Template parse error');
		}

		$placeholders = array_fill_keys($placeholders[1], '');

		return array_merge($placeholders, $data);
	}

	public function render()
	{
		if (!$this->template) {
			throw new \Exception('template missing');
		}

		$file = $this->basePath.$this->template.'.tpl';

		if (!file_exists($file)) {
			throw new \Exception('template file missing: ' . $file);
		}

		$body = file_get_contents($file);

		$data = $this->parseTemplate($body, $this->data);

		foreach ($data as $placeholder => $value) {

			// for lists
			if (is_array($value)) {
				$ret = '';
				foreach ($value as &$inner) {
					if ($inner instanceof Template) {
						$inner = $inner->render();
					}
					$ret .= $inner;
				}unset($inner);
				$value = $ret;
			}

			if ($value instanceof Template) {
				$value = $value->render();
			}

			$body = str_replace('{{' . $placeholder . '}}', $value, $body);
		}

		return $body;
	}
}