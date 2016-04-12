<?php
/**
 * Created by PhpStorm.
 * User: DrafFter
 * Date: 2016-04-12
 * Time: 19:31
 */

namespace Service;


use Arbor\Contener\ServiceConfig;
use Arbor\Core\Container;
use Entity\QueueEmail;

class Mail
{
	public function __construct(ServiceConfig $serviceConfig)
	{
		//do nothing
	}

	/**
	 * @param Container $container
	 * @param $to
	 * @param $subject
	 * @param $content
	 */
	public function send(Container $container, $to, $subject, $content)
	{
		$mail = new QueueEmail();
		$mail->setTo($to);
		$mail->setSubject($subject);
		$mail->setContent($content);
		$container->persist($mail);
		$container->flush();
	}
}