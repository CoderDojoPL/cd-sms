<?php

namespace Presenter;

use Arbor\Core\Presenter;
use Arbor\Provider\Response;
use Arbor\Contener\RequestConfig;

/**
 * Presenter for rest json response
 *
 * @author Michal Tomczak <m.tomczak@coderdojo.org.pl>
 */
class RESTJson implements Presenter{

	private $config;

	public function render(RequestConfig $config , Response $response){
		$response->setHeader('content-type','application/json');
		$this->config=$config;
		$this->setHeaders($response);
		if($response->getStatusCode()<401)
			$this->renderSuccess($response);
		else
			$this->renderFail($response);
	}

	private function renderSuccess(Response $response){
		echo json_encode($response->getContent());
	}

	private function renderFail(Response $response){
		if(!$this->config->isSilent())
			header('HTTP/1.1 '.$response->getStatusCode().' '.$response->getStatusMessage());

		if($response->getContent() instanceof \Exception){
			$exception=$response->getContent();
			$message='Błąd wewnętrzny!';
			if($exception instanceof \Arbor\Core\Exception){
				$message=$exception->getSafeMessage();
			}

			if($this->config->isDebug())
				$message=$exception->getMessage();

			$data=array('code'=>$exception->getCode()
				,'message'=>$message);
			if($this->config->isDebug()){
				$data=array_merge($data,array(
					'file'=>$exception->getFile()
					,'line'=>$exception->getLine()
					,'trace'=>$exception->getTraceAsString()
					,'exception'=>get_class($exception)
				));
			}

			echo json_encode($data);
			
		}
	}

	private function setHeaders($response){
		if(!$this->config->isSilent()){
			foreach($response->getHeaders() as $name=>$value){
				header($name.': '.$value);
			}

		}
	}
}