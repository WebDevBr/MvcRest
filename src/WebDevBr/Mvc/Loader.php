<?php

namespace WebDevBr\Mvc;

class Loader
{
	public function getController($app, $controller, $action, $params = [])
	{
		$route = [
			'controller'=>$controller,
			'action'=>$action
		];

		$controller = 'App\Mvc\Controllers\\' . ucfirst($controller) . "Controller";

		if (!class_exists($controller))
			$controller = 'WebDevBr\Mvc\Controllers\Rest';

		return call_user_func([new $controller($app, $route), $action], $params);
	}
}