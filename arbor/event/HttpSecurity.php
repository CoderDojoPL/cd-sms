<?php

namespace Arbor\Event;
use Arbor\Core\Event;
use Arbor\Provider\Response;
use Arbor\Event\ExecutedActionEvent;

/**
 * Event to setting security headers
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