<?php

namespace Arbor\Presenter;

use Arbor\Core\Presenter;
use Arbor\Provider\Response;
use Arbor\Contener\RequestConfig;

class JSON implements Presenter{

	public function render(RequestConfig $config , Response $response){
		$this->setHeaders();
		echo json_encode($response->getContent());
	}

	private function setHeaders(){
		header('Content-type: application/json');
		foreach($response->getHeaders() as $name=>$value){
			if($name!='content-type')
				header($name.': '.$value);
		}

	}
}