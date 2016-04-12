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
		$message=$mailer->createMessage($email->getSubject())
			->setFrom(array('mail@mail' => 'Serwer'))
			->setTo(array($email->getTo()))
			->setBody($email->getContent());
		$mailer->send($message);
		$email->setSendedAt(new \DateTime());
		$this->flush();

	}

}

?>
