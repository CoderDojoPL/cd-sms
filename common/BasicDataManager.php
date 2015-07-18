<?php

/*
 * This file is part of the HMS project.
 *
 * (c) CoderDojo Polska Foundation
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Common;

use Arbor\Component\Grid\GridDataManager;

/**
 * Data manager for grid. Loadig records from Doctrine service
 * @package Common
 * @author Michal Tomczak (m.tomczak@coderdojo.org.pl)
 */
class BasicDataManager implements GridDataManager{

	private $entityManager;
	private $storage;
	private $condition;

	/**
	 * @param Doctrine\ORM\EntityManager $entityManager
	 * @param string $storage - entity name e.g. User, Order
	 * @param string $condition - DQL query with conditions records
	 */
	public function __construct($entityManager,$storage,$condition=null){
		$this->entityManager=$entityManager;
		$this->storage=$storage;
		$this->condition=$condition;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getRecords($limit,$page){
		$result=array();

        $records=$this->entityManager->createQuery(
                'SELECT i FROM '.$this->storage.' i '.($this->condition?'WHERE '.$this->condition:'')
            )
        ->setMaxResults($limit)
        ->setFirstResult(($page-1)*$limit)
        ->getResult();

		foreach($records as $record){
			$result[]=$this->entityToArray($record);
		}

		return $result;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getTotalCount(){
		$records=$this->entityManager->createQuery(
                'SELECT count(i) as c FROM '.$this->storage.' i '.($this->condition?'WHERE '.$this->condition:'')
            )
        ->getResult();
        return $records[0]['c'];
	}

	/**
	 * Transform entity to array
	 *
	 * @param object $entity
	 * @return array
	 */
	private function entityToArray($entity){
		$values=array();
		foreach(get_class_methods($entity) as $method){
			if(preg_match('/^get(.*)$/',$method,$finds)){
				$data=$entity->$method();
				if(is_object($data)){

					if($data instanceof \DateTime)
						$data=$data->format('Y-m-d H:i:s');
					else if($data instanceof \Doctrine\ORM\PersistentCollection){
						
					}
					else
						$data=(string)$data;
				}


				$values[lcfirst($finds[1])]=$data;
			}
		}

		return $values;
	}

}