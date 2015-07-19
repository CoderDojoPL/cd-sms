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

namespace Arbor\Presenter;

use Arbor\Core\Presenter;
use Arbor\Provider\Response;
use Arbor\Contener\RequestConfig;

/**
 * Presenter for html.
 *
 * @author Michal Tomczak (michal.tomczak@arborphp.com)
 * @since 0.1.0
 */
class HTML implements Presenter{

	public function render(RequestConfig $config , Response $response){

		if(!$config->isSilent())
			header('HTTP/1.1 '.$response->getStatusCode().' '.$response->getStatusMessage());

		foreach($response->getHeaders() as $name=>$value){
			header($name.': '.$value);
		}

		echo (string)$response->getContent();
	}
}