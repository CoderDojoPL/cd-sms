<?php

namespace Mapper;
use Arbor\Core\Mapper;
use Exception\OrderStateNotFoundException;
class OrderState extends Mapper{
	

	public function cast($value){
		$entity=$this->getService('doctrine')->getEntityManager()->getRepository('Entity\OrderState')->findOneById($value);
		if(!$entity)
			throw new OrderStateNotFoundException();

		return $entity;
	}
}