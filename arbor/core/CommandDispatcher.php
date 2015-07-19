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
use Arbor\Provider\Request;
use Arbor\Contener\CommandConfig;

/**
 * Dispatcher for commands
 *
 * @author Michal Tomczak (michal.tomczak@arborphp.com)
 * @since 0.1.0
 */
class CommandDispatcher  implements Dispatcher {

	/**
	 * Request
	 *
	 * @var \Arbor\Provider\Request $request
	 */
	private $request;

	/**
	 * ExecuteResources
	 *
	 * @var \Arbor\Core\ExecuteResources $resources
	 */
	private $resources;

	/**
	 * CommandCOnfig
	 *
	 * @var \Arbor\Contener\CommandConfig $config
	 */
	private $config;

	/**
	 * Constructor.
	 *
	 * @param \Arbor\Contener\CommandConfig $config
	 * @param array $arguments command arguments
	 * @since 0.1.0
	 */
	public function __construct(CommandConfig $config,$arguments){
		$this->config=$config;
		$this->arguments=$arguments;
	}

	/**
	 * Execute command
	 *
	 * @param \Arbor\Core\ExecuteResources $resource
	 * @param \Arbor\Core\EventManager $eventManager
	 * @since 0.1.0
	 */
	public function execute(ExecuteResources $resources,EventManager $eventManager){
		$commandName=$this->config->getClass();
		$command=new $commandName($resources);
	
		if(!is_callable(array($command,$this->config->getMethod()))){
			throw new CommandNotFoundException($commandName,$this->config->getMethod());
		}

		call_user_func_array(array($command, $this->config->getMethod()), $this->arguments);
	}

}