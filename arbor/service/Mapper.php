<?php

namespace Arbor\Service;

use Arbor\Contener\ServiceConfig;
use Arbor\Core\Container;
use Arbor\Exception\MapperNotFoundException;

/**
 * Service to cast variables
 * @since 0.14.0
 */
class Mapper{
	
	/**
	 * @arg serviceConfig - contener config
	 * @since 0.13.0
	*/
	public function __construct(ServiceConfig $serviceConfig){		
	}

	/**
	 * cast value
	 * @arg container
	 * @arg mapperName - class with implements mapped code eg: "Arbor\Mapper\Text"
	 * @arg value - value to cast
	 * @since 0.14.0
	 */
	public function cast(Container $container,$mapperName,$value){
		if(!class_exists($mapperName))
			throw new MapperNotFoundException($mapperName);

		$mapper=new $mapperName($container);
		return $mapper->cast($value);
	}

}