<?php

namespace Arbor\Core;

use Arbor\Root;
use Arbor\Test\Request;
use Arbor\Core\Enviorment;

require __DIR__.'/../Root.php';

abstract class WebTestCase extends \PHPUnit_Framework_TestCase{
	public function __construct(){
		$this->root=new Root(true,true,'test');
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

		return new Request($url,$this->root,new Enviorment(true,true,'test'));
	}

}