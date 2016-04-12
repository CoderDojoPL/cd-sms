<?php

/*
 * This file is part of the ArborPHP.
 * Copyright (c) NewClass (http://newclass.pl)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Library\Twig\Service;

use Arbor\Contener\ServiceConfig;
use Arbor\Core\EventManager;

require_once __DIR__.'/../engine/Twig/Autoloader.php';

class Twig{
	
	private $engine;

	public function __construct(ServiceConfig $serviceConfig,EventManager $eventManager){
		\Twig_Autoloader::register();
		$loader = new \Twig_Loader_Filesystem(__DIR__.'/../../../template');
		$this->engine = new \Twig_Environment($loader);

	}

	public function render($template,$data){
		return $this->engine->render($template, $data);
	}

}