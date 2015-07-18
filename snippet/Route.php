<?php

/*
 * This file is part of the HMS project.
 *
 * (c) CoderDojo Polska Foundation
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Snippet;
use Arbor\Provider\Response;
use Arbor\Core\Container;

/**
 * @package Snippet
 * @author Michal Tomczak (m.tomczak@coderdojo.org.pl)
 */
class Route {
	
	/**
	 * create response with configure redirect action
	 *
	 * @param \Arbor\Core\Container $container
	 * @param string $url - destiny http address
	 * @return \Arbor\Provider\Response
	 */
	public function redirect(Container $container,$url){
		$response=new Response();
		$response->redirect($url);
		return $response;
	}

}
