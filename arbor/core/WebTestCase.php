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

use Arbor\Root;
use Arbor\Test\Request;
use Arbor\Core\Enviorment;
use Arbor\Test\BrowserEmulator;
use Arbor\Provider\Session;

require __DIR__.'/../Root.php';

/**
 * Helper for functionalit web test
 *
 * @author Michal Tomczak (michal.tomczak@arborphp.com)
 * @since 0.1.0
 */
abstract class WebTestCase extends \PHPUnit_Framework_TestCase{
	
	private $root;
	private $enviorment;
	public function __construct(){
		$this->root=new Root(true,true,'test');
		$this->enviorment=new Enviorment(true,true,'test');
	}

	protected function getService($name){
		return $this->root->getService($name);
	}

	protected function executeCommand(){
		ob_start();
		$this->root->executeCommand(func_get_args());
		$result=ob_get_clean();
		ob_flush();
		return $result;
	}

	protected function createRequest($url){

		return new Request($url,$this->enviorment);
	}

	protected function createClient(Session $session=null){
		return new BrowserEmulator($this->enviorment,$session);
	}

	protected function createSession(){
		return new Session($this->enviorment);
;
	}

}