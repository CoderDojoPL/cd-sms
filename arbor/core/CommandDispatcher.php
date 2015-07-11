<?php


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

class CommandDispatcher  implements Dispatcher {
	private $request;
	private $resources;
	private $config;

	public function __construct(CommandConfig $config,$arguments){
		$this->config=$config;
		$this->arguments=$arguments;
	}

	public function execute(ExecuteResources $resources,EventManager $eventManager){
		$commandName=$this->config->getClass();
		$command=new $commandName($resources);
	
		if(!is_callable(array($command,$this->config->getMethod()))){
			throw new CommandNotFoundException($commandName,$this->config->getMethod());
		}

		call_user_func_array(array($command, $this->config->getMethod()), $this->arguments);
	}

}