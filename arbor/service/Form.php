<?php

namespace Arbor\Service;

use Arbor\Contener\ServiceConfig;
use Arbor\Component\Form\FormBuilder;

/**
 * Service to construct and support form
 * @since 0.13.0
 */
class Form{
	
	/**
	 * @arg serviceConfig - contener config
	 * @since 0.13.0
	*/
	public function __construct(ServiceConfig $serviceConfig){		
	}

	/**
	 * create instance builder
	 * @since 0.13.0
	 */
	public function create(){
		return new FormBuilder();
	}

}