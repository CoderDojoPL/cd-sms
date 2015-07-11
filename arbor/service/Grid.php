<?php

namespace Arbor\Service;

use Arbor\Contener\ServiceConfig;
use Arbor\Component\Grid\GridBuilder;

/**
 * Service to construct and support grid
 * @since 0.17.0
 */
class Grid{
	
	/**
	 * @arg serviceConfig - contener config
	 * @since 0.17.0
	*/
	public function __construct(ServiceConfig $serviceConfig){		
	}

	/**
	 * create instance builder
	 * @since 0.17.0
	 */
	public function create(){
		return new GridBuilder();
	}

}