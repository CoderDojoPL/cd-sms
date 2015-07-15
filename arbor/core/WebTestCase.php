<?php

namespace Arbor\Core;

use Arbor\Root;
use Arbor\Test\Request;
use Arbor\Core\Enviorment;
use Arbor\Test\BrowserEmulator;
use Arbor\Provider\Session;

require __DIR__.'/../Root.php';

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