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
use Arbor\Component\Form\FormBuilder;

/**
 * Service to construct and support form
 *
 * @author Michal Tomczak (michal.tomczak@arborphp.com)
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