<?php

namespace Library\Doctrine;

use Arbor\Core\EventManager;
use Doctrine\ORM\Event\OnFlushEventArgs;

class EventHandler{
	
	private $eventManager;

	public function __construct(EventManager $eventManager){
		$this->eventManager=$eventManager;
	}

	public function onFlush(OnFlushEventArgs $eventArgs){
		$this->eventManager->fire('doctrine.onFlush',$eventArgs);

	}
}
