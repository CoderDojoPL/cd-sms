<?php


namespace Arbor\Core;
use Arbor\Contener\RequestConfig;
use Arbor\Core\ExecuteResources;
use Arbor\Core\EventManager;

interface Dispatcher {

	public function execute(ExecuteResources $resources,EventManager $eventManager);
}