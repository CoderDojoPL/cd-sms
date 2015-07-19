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
use Arbor\Contener\RequestConfig;
use Arbor\Core\ExecuteResources;
use Arbor\Core\EventManager;

/**
 * Interface dispatcher
 *
 * @author Michal Tomczak (michal.tomczak@arborphp.com)
 * @since 0.1.0
 */
interface Dispatcher {

	/**
	 * Execute dispatcher
	 *
	 * @param \Arbor\Core\ExecuteResources $resources
	 * @param \Arbor\Core\EventManager $eventManager
	 * @since 0.1.0
	 */
	public function execute(ExecuteResources $resources,EventManager $eventManager);
}