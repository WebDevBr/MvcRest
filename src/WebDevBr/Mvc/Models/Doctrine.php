<?php

namespace WebDevBr\Mvc\Models;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use WebDevBr\DesignPatters\Singleton;

class Doctrine extends Singleton
{
	public static function getInstance()
	{
		if (null === static::$instance) {
			$path = unserialize(ENTITY_PATH);
			$db_params = unserialize(DB_PARAMS);
			$config = Setup::createAnnotationMetadataConfiguration($path, DEBUG);
			$entityManager = EntityManager::create($db_params, $config);
			static::$instance = $entityManager;
		}

		return static::$instance;
	}
}