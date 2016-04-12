<?php

namespace Command;

use Arbor\Core\Command;

class QueueEmail extends Command{

	public function send(){
		$emails=$this->find('QueueEmail',array('sendedAt'=>null));
		foreach($emails as $email){
			$this->sendEmail($email);
		}
	}

	private function sendEmail($email){
		$mailer=$this->getService('swiftmailer');
		$config=$this->getService('config');
		$from=$config->getSenderEmailAddress();
		$message=$mailer->createMessage($email->getSubject())
			->setFrom(array($from=>$from))
			->setTo(array($email->getTo()))
			->setBody($email->getContent());
		$mailer->send($message);
		$email->setSendedAt(new \DateTime());
		$this->flush();

	}

}

?>
