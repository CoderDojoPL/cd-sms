<?php

namespace Mapper;
use Arbor\Core\Mapper;
use Exception\DeviceTypeNotFoundException;
class DeviceType extends Mapper{
	

	public function cast($value){
		$entity=$this->getService('doctrine')->getEntityManager()->getRepository('Entity\DeviceType')->findOneById($value);
		if(!$entity)
			throw new DeviceTypeNotFoundException();

		return $entity;
	}
}