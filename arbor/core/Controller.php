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

use Arbor\Core\ContenerServices;
use Arbor\Core\RequestProvider;
use Arbor\Provider\Response;
use Arbor\Provider\Session;
use Arbor\Exception\ServiceNotFoundException;
use Arbor\Exception\MethodNotFoundException;
use Arbor\Core\ExecuteResources;

/**
 * Main class for project controllers
 *
 * @author Michal Tomczak (michal.tomczak@arborphp.com)
 * @since 0.1.0
 */
abstract class Controller extends Container{

	private $request;
	private $session;
	private $services=array();
	private $snippets=array();	

	public function __construct(RequestProvider $request, ExecuteResources $executeResources){
		$this->request=$request;
		$this->session=$request->getSession();
		parent::__construct($executeResources);
	}

	public function getRequest(){
		return $this->request;
	}

	public function getSession(){
		return $this->session;
	}
}