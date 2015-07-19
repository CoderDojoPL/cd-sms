<?php

/**
 * ArborPHP: Freamwork PHP (http://arborphp.com)
 * Copyright (c) NewClass (http://newclass.pl)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the file LICENSE
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) NewClass (http://newclass.pl)
 * @link          http://arborphp.com ArborPHP Project
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace Arbor\Service;

use Arbor\Contener\ServiceConfig;
use Arbor\Core\Container;
use Arbor\Exception\MapperNotFoundException;

/**
 * Service to cast variables
 *
 * @author Michal Tomczak (michal.tomczak@arborphp.com)
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