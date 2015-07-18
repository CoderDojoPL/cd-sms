<?php

/*
 * This file is part of the HMS project.
 *
 * (c) CoderDojo Polska Foundation
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Service;

use Arbor\Contener\ServiceConfig;

/**
 * Helper for FormBuilder
 *
 * @package Service
 * @author Michal Tomczak (m.tomczak@coderdojo.org.pl)
 */
class FormHelper{
	

	/**
	 * @param \Arbor\Contener\ServiceConfig $serviceConfig
	 */
	public function __construct(ServiceConfig $serviceConfig){		
	}

	/**
	 * Transform entity to FormBuilder collection
	 *
	 * @param array $storage - records list
	 * @param array $appendRows - extra records on top collection
	 * @param string $labelMethod - method name to create label string
	 */
	public function entityToCollection($storage,$appendRows=array(),$labelMethod='__toString'){
		$values=array();
		foreach($appendRows as $record){
			$values[]=array('value'=>$record[0],'label'=>$record[1]);

		}

		foreach($storage as $record){
			$values[]=array('value'=>$record->getId(),'label'=>htmlspecialchars($record->$labelMethod()));
		}

		return $values;
	}

	/**
	 * Transform array to FormBuilder collection
	 *
	 * @param array $storage - records list
	 * @param array $appendRows - extra records on top collection
	 * @param string $labelMethod - method name to create label string
	 * @param string $valueField - array index name to get record id
	 */
	public function arrayToCollection($storage,$appendRows=array(),$labelField='name',$valueField='id'){
		$values=array();
		foreach($appendRows as $record){
			$values[]=array('value'=>$record[0],'label'=>$record[1]);

		}

		foreach($storage as $record){
			$values[]=array('value'=>$record[$valueField],'label'=>htmlspecialchars($record[$labelField]));
		}

		return $values;
	}

	/**
	 * Transform entity to array
	 *
	 * @param array $entity
	 * @param array $mapped - array with method name to create label string for entity field
	 */
	public function entityToArray($entity,$mapped=array()){
		$values=array();
		foreach(get_class_methods($entity) as $method){
			if(preg_match('/^get(.*)$/',$method,$finds)){
				$data=$entity->$method();
				if(is_object($data)){

					if(isset($mapped[get_class($data)]))
						$data=$data->$mapped[get_class($data)]();
					else if(method_exists($data, 'getId'))
						$data=$data->getId();
					else if($data instanceof \DateTime)
						$data=$data->format('Y-m-d');
					else if($data instanceof \Doctrine\ORM\PersistentCollection){
						$records=array();
						foreach($data as $record){
							if(isset($mapped[get_class($record)]))
								$records[]=$record->$mapped[get_class($record)]();
							else
								$records[]=$record->getId();
						}
						$data=$records;
					}
					else
						continue;
				}


				$values[lcfirst($finds[1])]=$data;
			}
		}

		return $values;
	}

}