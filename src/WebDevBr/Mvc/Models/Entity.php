<?php

namespace WebDevBr\Mvc\Models;

abstract class Entity
{
	public function __construct(Array $data = [])
	{
		$this->setAll($data);
	}

	public function getAll()
	{
		$data = [];

		foreach ($this as $k=>$v) {
			$first = (substr($k, 0, 1));
			if ($first !== '_')
				$data[$k]=$v;
		}
		
		return $data;
	}

	public function setAll(Array $data)
	{
		foreach ($data as $k=>$v)
			if (property_exists($this, $k)) $this->$k = $v;
	}
}