<?php

namespace Snippet;
use Arbor\Core\Controller;
use Arbor\Provider\Response;
use Arbor\Core\Container;

class Route {
	
	public function redirect(Controller $controller,$url){
		$response=new Response();
		$response->redirect($url);
		return $response;
	}

}
