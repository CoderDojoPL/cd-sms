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
 * Contener with data for event "executeAction"
 *
 * @author Michal Tomczak (michal.tomczak@arborphp.com)
 * @since 0.1.0
 */
class ExecuteActionEvent{

	/**
	 * Request provider.
	 *
	 * @var \Arbor\Core\RequestProvider $request
	 */
	private $request;

	/**
	 * Response.
	 *
	 * @var \Arbor\Provider\Response $response
	 */
	private $response;

	/**
	 * Constructor.
	 *
	 * @param \Arbor\Core\RequestProvider $request
	 * @since 0.1.0
	 */
	public function __construct(RequestProvider $request){
		$this->request=$request;
	}

	/**
	 * Get request.
	 *
	 * @return \Arbor\Core\RequestProvider
	 * @since 0.1.0
	 */
	public function getRequest(){
		return $this->request;
	}

	/**
	 * Set response.
	 *
	 * @param \Arbor\Provider\Response $response
	 * @since 0.1.0
	 */
	public function setResponse(Response $response){
		$this->response=$response;
	}

	/**
	 * Get response.
	 *
	 * @return \Arbor\Provider\Response
	 * @since 0.1.0
	 */
	public function getResponse(){
		return $this->response;
	}
}