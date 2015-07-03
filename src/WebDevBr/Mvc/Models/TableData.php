<?php

namespace WebDevBr\Mvc\Models;


class TableData
{
	final public function __construct($controller_origin = null)
	{
		if(empty($this->entity)){
			$inflector = \ICanBoogie\Inflector::get();
			$entity = $inflector->singularize($inflector->camelize($controller_origin));
			$this->entity = '\App\Mvc\Models\Entities\\' . $entity;
		}
	}

	protected function getEntity($instance = false, Array $data = [])
	{
		if ($instance)
			return new $this->entity($data);

		return $this->entity;
	}

	public function find($conditions = null, Array $config = null)
	{
		$one = false;
		if (isset($config['one']))
			$one = $config['one'];

		$to_array = false;
		if (isset($config['to_array']))
			$to_array = $config['to_array'];

		$em = Doctrine::getInstance();
		$repo = $em->getRepository($this->getEntity());

		$method = 'findBy';
		if ($one)
			$method = 'findOneBy';

		if (is_int($conditions)) 
			$conditions = ['id'=>$conditions];

		if (!is_array($conditions))
			$conditions = [];

		if (!$to_array)
			return call_user_func([$repo, $method], $conditions);

		return $repo->createQueryBuilder('e')
			->select('e')
			->getQuery()
			->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
	}

	public function insert($data)
	{
		$entity = $this->getEntity(true, $data);
		
		$em = Doctrine::getInstance();
		$em->persist($entity);
		$em->flush();

		return $entity;
	}

	public function update($id, $data)
	{
		$em = Doctrine::getInstance();

		if (!$this->count($id))
			return null;

		$entity = $em->getReference($this->getEntity(), $id);
		$entity->setAll($data);

		$em->persist($entity);
		$em->flush();

		return $entity;
	}

	public function delete($id)
	{
		$em = Doctrine::getInstance();

		if (!$this->count($id))
			return null;

		$entity = $em->getReference($this->getEntity(), $id);

		$em->remove($entity);
		$em->flush();

		return true;
	}

	public function getRepository()
	{
		$em = Doctrine::getInstance();
		return $em->getRepository($this->getEntity());
	}

	public function count($id)
	{
		$em = Doctrine::getInstance();
		$repo = $em->getRepository($this->getEntity());
		$count = $repo->createQueryBuilder('e')
			->select('COUNT(e)')
			->where('e.id = :id')
			->setParameter('id', $id)
			->getQuery()
			->getResult();

		return $count[0][1];
	}
}