<?php
namespace views;


class MainView extends Template
{
	protected $template = 'layout';

	public function render()
	{
		$this->data['top_nav'] = new Template($this->di, [], 'top_nav');
		$this->data['header'] = '';

		return parent::render();
	}

}