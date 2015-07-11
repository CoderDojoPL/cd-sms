<?php

namespace Mapper;
use Arbor\Core\Mapper;
use Exception\UserNotFoundException;
class User extends Mapper{
	

	public function cast($value){
		$entity=$this->getService('doctrine')->getEntityManager()->getRepository('Entity\User')->findOneById($value);
		if(!$entity)
			throw new UserNotFoundException();

		return $entity;
	}
}