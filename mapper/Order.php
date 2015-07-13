<?php

namespace Mapper;
use Arbor\Core\Mapper;
use Exception\OrderNotFoundException;
class Order extends Mapper{
	

	public function cast($value){
		$entity=$this->getService('doctrine')->getEntityManager()->getRepository('Entity\Order')->findOneById($value);
		if(!$entity)
			throw new OrderNotFoundException();

		return $entity;
	}
}