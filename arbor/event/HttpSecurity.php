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
use Arbor\Core\Event;
use Arbor\Provider\Response;
use Arbor\Event\ExecutedActionEvent;

/**
 * Event to setting security headers
 *
 * @author Michal Tomczak (michal.tomczak@arborphp.com)
 * @since 0.14.0
 */
class HttpSecurity extends Event{
	
	public function onExecutedAction(ExecutedActionEvent $event,$config){
		$response=$event->getResponse();
		$this->supportTimeForceSSL($response,$config);
	}

	private function supportTimeForceSSL(Response $response, $config){
		if(isset($config['forceSSL']) && $config['forceSSL']=="true"){
			$maxAge='3600000';
			if(isset($config['timeForceSSL']))
				$maxAge=$config['timeForceSSL'];

			$response->setHeader('Strict-Transport-Security','max-age='.$maxAge.'; includeSubDomains');
		}

	}

}