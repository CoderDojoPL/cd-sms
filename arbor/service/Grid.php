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
use Arbor\Component\Grid\GridBuilder;
use Arbor\Core\RequestProvider;

/**
 * Service to construct and support grid
 *
 * @author Michal Tomczak (michal.tomczak@arborphp.com)
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
	 * @param RequestProvider $request
	 * @since 0.17.0
	 */
	public function create(RequestProvider $request){
		return new GridBuilder($request);
	}

}