<?php

namespace WebDevBr\Mvc\View;

use Slim\View;

class Json extends View
{
	public function render($template = [], $data = null)
	{
		if (is_string($template))
			return parent::render($template, $data);

		return json_encode($this->data[0]);
	}
}