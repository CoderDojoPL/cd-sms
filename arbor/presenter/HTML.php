<?php

namespace Arbor\Presenter;

use Arbor\Core\Presenter;
use Arbor\Provider\Response;
use Arbor\Contener\RequestConfig;

class HTML implements Presenter{

	public function render(RequestConfig $config , Response $response){

		if(!$config->isSilent())
			header('HTTP/1.1 '.$response->getStatusCode().' '.$response->getStatusMessage());

		foreach($response->getHeaders() as $name=>$value){
			header($name.': '.$value);
		}

		echo (string)$response->getContent();
	}
}