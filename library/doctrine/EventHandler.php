<?php

/*
 * This file is part of the ArborPHP.
 * Copyright (c) NewClass (http://newclass.pl)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
