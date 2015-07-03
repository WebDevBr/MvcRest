<?php

namespace WebDevBr\Mvc\Controllers;

class Rest
{
	protected $app;
	protected $route;

	public function __construct($app, Array $route = null)
	{
		$this->app = $app;
		$this->route = $route;
	}

	public function viewAll()
	{
		$data = $this->getModel()->find(null, ['to_array'=>true]);

		return [
			'data'=>$data
		];
	}

	public function viewOne($id)
	{
		return $this->getData($id);
	}

	public function create()
	{
		$data = $this->app->request->post();
		$data = array_merge($data, $this->app->request->put());

		$entity = $this->getModel()->insert($data);

		return [
			'data'=>$entity->getAll()
		];
	}

	public function update($id)
	{
		$data = $this->app->request->post();
		$data = array_merge($data, $this->app->request->put());

		$entity = $this->getModel()->update($id, $data);
		
		if (!$entity)
			return $this->set404($entity);

		return [
			'data'=>$entity->getAll()
		];
	}

	public function delete($id)
	{
		if ($return = $this->getModel()->delete($id))
			return [
				'message'=>'deleted'
			];

		return $this->set404($return);
	}

	protected function getData($id)
	{
		$data = $this->getModel()
				->find((int)$id, ['one'=>true]);
		if ($data)
			return ['data'=>$data->getAll()];

		return $this->set404($data);
	}

	protected function set404($data)
	{
		if (!$data) {
			$this->app->response->setStatus(404);
			return ['data'=>'not found'];
		}
	}

	protected function getModel()
	{
		if (empty($this->model))
			$this->model = new \WebDevBr\Mvc\Models\TableData($this->route['controller']);

		if (is_string($this->model))
			$this->model = new $this->model;

		return $this->model;
	}
}