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
class DqlDataManager implements GridDataManager{

	private $entityManager;

	/**
	 * @param Doctrine\ORM\EntityManager $entityManager
	 * @param string $dql
	 */
	public function __construct($entityManager,$dql,$dqlCount,$vars=array()){
		$this->entityManager=$entityManager;
		$this->dql=$dql;
		$this->dqlCount=$dqlCount;
		$this->vars=$vars;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getRecords($limit,$page){
		$result=array();

        $records=$this->entityManager->createQuery(
                $this->dql
		)
			->setParameters($this->vars)
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
			$this->dqlCount
            )
			->setParameters($this->vars)
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
		if(is_array($entity))
			return $entity;

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