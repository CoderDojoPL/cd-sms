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
use Exception\OrderNotFoundException;

/**
 * Cast id to Entity\Order
 *
 * @package Mapper
 * @author Slawomir Nowak (s.nowak@coderdojo.org.pl)
 * @author Michal Tomczak (m.tomczak@coderdojo.org.pl)
 */
class Order extends Mapper{
	

	/**
	 * {@inheritdoc}
	 */
	public function cast($value){
		$entity=$this->getService('doctrine')->getEntityManager()->getRepository('Entity\Order')->findOneById($value);
		if(!$entity)
			throw new OrderNotFoundException();

		return $entity;
	}
}