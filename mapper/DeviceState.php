<?php

namespace Mapper;
use Arbor\Core\Mapper;
use Exception\DeviceStateNotFoundException;
class DeviceState extends Mapper{
	

	public function cast($value){
		$entity=$this->getService('doctrine')->getEntityManager()->getRepository('Entity\DeviceState')->findOneById($value);
		if(!$entity)
			throw new DeviceStateNotFoundException();

		return $entity;
	}
}