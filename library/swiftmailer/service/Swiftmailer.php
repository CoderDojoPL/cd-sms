<?php

namespace Library\Swiftmailer\Service;

require_once __DIR__.'/../engine/lib/swift_required.php';

use Arbor\Contener\ServiceConfig;
use Arbor\Core\EventManager;


class Swiftmailer{	

	private $config;
	private $mailer;
	public function __construct(ServiceConfig $serviceConfig,EventManager $eventManager){		
		$this->config=$serviceConfig;
	}

	public function createMessage($subject){
		return \Swift_Message::newInstance($subject);
	}

	public function send($message){
		return $this->getConnect()->send($message);
	}

	private function getConnect(){
		if(!$this->mailer){
			$transporter = \Swift_SmtpTransport::newInstance($this->config->get('host'), $this->config->get('port'), ($this->config->get('ssl')=='true'?'ssl':null))
			  ->setUsername($this->config->get('username'))
			  ->setPassword($this->config->get('password'));

			$this->mailer = \Swift_Mailer::newInstance($transporter);

		}

		return $this->mailer;

	}

}