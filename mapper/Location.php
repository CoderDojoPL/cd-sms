<?php

namespace Mapper;
use Arbor\Core\Mapper;
use Exception\LocationNotFoundException;
class Location extends Mapper{
	

	public function cast($value){
		$entity=$this->getService('doctrine')->getEntityManager()->getRepository('Entity\Location')->findOneById($value);
		if(!$entity)
			throw new LocationNotFoundException();

		return $entity;
	}
}