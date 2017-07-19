<?php

namespace controllers;

use libs\Application\AjaxResponse;
use libs\Application\HttpResponse;
use libs\Uploader\Http;
use views\MainView;
use views\Task\TaskListView;
use views\Task\TaskItemView;

class Task extends Base
{
	public function listAction(){

		$tasks = (new \models\Task($this->di->get('mysql'), $this->di))->getList(0,5,['username' => 'asc']);

		$taskListItems = [];
		foreach ($tasks as $task) {
			$taskListItems[] = new TaskItemView($this->di, $task);
		}

		$taskList = new TaskListView($this->di, ['task_list' => $taskListItems]);

		if ($this->request->isAjax()) {
			$response = new AjaxResponse();
		} else {
			$response = new HttpResponse();
		}

		$page = new MainView($this->di, ['body' => $taskList]);

		return $response->setBody($page->render())->setStatus(200);
	}

	public function saveAction(){

		$response = new HttpResponse();

		$task = new \models\Task($this->di->get('mysql'), $this->di);

		$task->id          = $this->request->get('id');
		$task->usesrname   = $this->request->get('username');
		$task->email       = $this->request->get('email');
		$task->body        = $this->request->get('body');
		$task->status      = $this->request->get('status') == 'on'?'close':'open';

		$task->save();

		$uploader = new Http($this->request->getFile('image'));

		if (!$uploader->validate()) {
			exit;
		}

		$uploader->createImage();
		$ext = $uploader->saveImage(BASE_PATH . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . $task->id . '_image');
		$task->image .= '.'.$ext;

		$task->save();

		$response->setStatus(302);
	}

	public function addAction(){

		$response = new HttpResponse();

		$view = new TaskItemView(
			$this->di,
			[],
			'Task/task_item_edit'
		);

		$page = new MainView($this->di, ['body' => $view]);

		return $response->setBody($page->render())->setStatus(200);
	}

	public function editAction(){

		if ($this->request->isAjax()) {
			$response = new AjaxResponse();
		} else {
			$response = new HttpResponse();
		}

		$task = (new \models\Task($this->di->get('mysql'), $this->di))->getOne($this->request->get('id'));

		$task['status_check'] = $task['status'] != 'open'?'checked=checked':'';

		if (!$task) {
			return $response->setStatus(404);
		}

		$view = new TaskItemView(
			$this->di,
			$task,
			'Task/task_item_edit'
		);

		$page = new MainView($this->di, ['body' => $view]);

		return $response->setBody($page->render())->setStatus(200);
	}
}