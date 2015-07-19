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

namespace Arbor\Event;

use Arbor\Core\RequestProvider;
use Arbor\Provider\Response;

/**
 * Contener with data for event "executePresenter"
 *
 * @author Michal Tomczak (michal.tomczak@arborphp.com)
 * @since 0.1.0
 */
class ExecutePresenterEvent{
	private $request;
	private $response;

	public function __construct(RequestProvider $request,Response $response){
		$this->request=$request;
		$this->response=$response;
	}

	public function getRequest(){
		return $this->request;
	}

	public function setResponse(Response $response){
		$this->response=$response;
	}

	public function getResponse(){
		return $this->response;
	}
}