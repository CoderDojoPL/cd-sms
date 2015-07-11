<?php

namespace Mapper;
use Arbor\Core\Mapper;
use Exception\DeviceNotFoundException;
class Device extends Mapper{
	

	public function cast($value){
		$entity=$this->getService('doctrine')->getEntityManager()->getRepository('Entity\Device')->findOneById($value);
		if(!$entity)
			throw new DeviceNotFoundException();

		return $entity;
	}
}