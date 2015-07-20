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

namespace Arbor\Core;

use Arbor\Core\ExecuteResources;
use Arbor\Provider\Response;
use Arbor\Event\ExecuteActionEvent;
use Arbor\Event\ExecutedActionEvent;
use Arbor\Event\ExecutePresenterEvent;
use Arbor\Core\EventManager;
use Arbor\Exception\ActionNotFoundException;
use Arbor\Provider\Session;
use Arbor\Test\Request;
use Arbor\Contener\RequestConfig;

/**
 * Dispatcher for phpunit http request.
 *
 * @author Michal Tomczak (michal.tomczak@arborphp.com)
 * @since 0.1.0
 */
class HttpTestDispatcher  extends HttpDispatcher {

	/**
	 * Set Request test
	 *
	 * @param \Arbor\Test\Request $request
	 * @since 0.1.0
	 */
	public function setRequest(Request $request){
		$this->request=$request;
	}

	/**
	 * {@inheritDoc}
	 *
	 * @param \Arbor\Core\ExecuteResources $resources
	 * @param \Arbor\Core\EventManager $eventManager
	 * @since 0.1.0
	 */
	public function execute(ExecuteResources $resources,EventManager $eventManager){
		$this->resources=$resources;
		$this->eventManager=$eventManager;
		$this->request->setConfig($this->config);
		$this->resources->registerRequest($this->request);

		$this->callMethod();

	}	

}