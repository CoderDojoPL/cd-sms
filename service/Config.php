<?php

/*
 * This file is part of the HMS project.
 *
 * (c) CoderDojo Polska Foundation
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Service;

use Arbor\Contener\ServiceConfig;

/**
 * Config container
 *
 * @package Service
 * @author Michal Tomczak (m.tomczak@coderdojo.org.pl)
 */
class Config{
	private $host;
	private $senderEmailAddress;


	/**
	 * @param \Arbor\Contener\ServiceConfig $serviceConfig
	 */
	public function __construct(ServiceConfig $serviceConfig){
		$this->host=$serviceConfig->get('host');
		$this->senderEmailAddress=$serviceConfig->get('senderEmailAddress');

	}

	public function getHost(){
		return $this->host;
	}

	public function getSenderEmailAddress(){
		return $this->senderEmailAddress;
	}
}