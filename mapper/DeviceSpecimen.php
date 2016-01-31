<?php

/*
 * This file is part of the HMS project.
 *
 * (c) CoderDojo Polska Foundation
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mapper;

use Arbor\Core\Mapper;
use Exception\DeviceSpecimenNotFoundException;

/**
 * Cast id to Entity\DeviceSpecimen
 *
 * @package Mapper
 * @author Michal Tomczak (m.tomczak@coderdojo.org.pl)
 */
class DeviceSpecimen extends Mapper{
	

	/**
	 * {@inheritdoc}
	 */
	public function cast($value){
		$entity=$this->getService('doctrine')->getEntityManager()->getRepository('Entity\DeviceSpecimen')->findOneById($value);
		if(!$entity)
			throw new DeviceSpecimenNotFoundException();

		return $entity;
	}
}