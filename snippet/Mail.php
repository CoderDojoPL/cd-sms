<?php
/**
 * Created by PhpStorm.
 * User: DrafFter
 * Date: 2016-04-12
 * Time: 19:38
 */

namespace Snippet;


class Mail
{
	/**
	 * input mail to queue
	 *
	 * @param \Arbor\Core\Container|Container $container
	 * @param $to
	 * @param $subject
	 * @param $content
	 * @return \Arbor\Provider\Response
	 * @throws \Arbor\Exception\ServiceNotFoundException
	 * @internal param string $url - destiny http address
	 */
	public function send(Container $container, $to, $subject, $content){
		$container->getService('mail')->send($container, $to, $subject, $content);
	}
}