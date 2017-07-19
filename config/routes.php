<?php
// route rules
return [
	'routes' => [
		'/' => [
			'controller' => 'task',
			'action' => 'list',
		],
		'/task/item' => [
			'controller' => 'task',
			'action' => 'item',
		],
		'/task/edit' => [
			'controller' => 'task',
			'action' => 'edit',
		],
	]
];