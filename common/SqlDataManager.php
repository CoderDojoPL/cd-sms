<?php

namespace Common;

use Arbor\Component\Grid\GridDataManager;
use Arbor\Core;
class SqlDataManager implements GridDataManager{
	private $container;
	private $storage;
	private $condition;

	public function __construct(Container $container,$sql,$condition){
		$this->container=$container;
		$this->sql=$sql;
		$this->condition=$condition;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getRecords($limit,$page){
		$result=array();

        $records=$this->entityManager->createQuery(
                'SELECT * FROM ('.$sql.') data '.($this->condition?'WHERE '.$this->condition:'')
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
;
	}

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