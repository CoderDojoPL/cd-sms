<?php

namespace Library\Twig\Presenter;
require_once '../library/twig/engine/Twig/Autoloader.php';

use Arbor\Core\Presenter;
use Arbor\Provider\Response;
use Arbor\Contener\RequestConfig;

class Twig implements Presenter{

	public function render(RequestConfig $requestConfig , Response $response){
		$this->config=$requestConfig;
		if(!$requestConfig->isSilent())
			header('HTTP/1.1 '.$response->getStatusCode().' '.$response->getStatusMessage());

		switch($response->getStatusCode()){
			case 300:
			case 301:
			case 302:
			case 303:
			case 305:
			case 307:
				$this->redirect($response);
			break;
			default:
				if($response->getStatusCode()>=400)
					$this->displayError($response);
				else
					$this->displaySuccess($response);
		}
	}

	private function redirect(Response $response){
		if(!$this->config->isSilent())
			header('Location: '.$response->getHeader('location'));
	}

	private function displaySuccess(Response $response){
		$presenterConfig=$this->config->getPresenter();
		\Twig_Autoloader::register();
		$loader = new \Twig_Loader_Filesystem('../template'); //TODO przekazywać do presentera config
		$twig = new \Twig_Environment($loader);

		$data=$response->getContent();
		if(!is_array($data)){
			$data=array('_functionalities'=>array());
		}
		else{
			if(!isset($data['_functionalities'])){
				$data['_functionalities']=array();
			}

		}
		$twig->addFunction('isAllow',$this->createIsAllowFunction($data['_functionalities']));

		echo $twig->render($this->config->getController().'/'.$this->config->getMethod().'.twig', $data);

	}

	private function displayError(Response $response){
		$presenterConfig=$this->config->getPresenter();
		\Twig_Autoloader::register();
		$loader = new \Twig_Loader_Filesystem('../template'); //TODO przekazywać do presentera config
		$twig = new \Twig_Environment($loader);
		$exception=$response->getContent();

		$data=array(
			'statusCode'=>$response->getStatusCode()
			,'message'=>$exception->getMessage()
			,'exception'=>get_class($exception)
			,'file'=>$exception->getFile()
			,'line'=>$exception->getLine()
			);

		if($this->config->isDebug())
			echo $twig->render('error.twig', $data);
		else
			echo $twig->render($response->getStatusCode().'.twig');

	}


	private function createIsAllowFunction($functionalities){
		return new \Twig_SimpleFunction('isAllow', function ($requireFunctionalities) use($functionalities){
			if(!is_array($requireFunctionalities))
				$requireFunctionalities=array($requireFunctionalities);
			
			foreach($requireFunctionalities as $requireFunctionality){
				if(isset($functionalities[$requireFunctionality]))
					return true;
			}
			return false;

		});
	}

	private function isAllow($userId,$functionalities){
		$doctrine=$this->getService('doctrine');
		$user=$doctrine->getRepository('Entity\User')->findOneById($userId);
		if(!$user)
			return true; //jeżeli nie jest zalogowany i nie wymaga autentykacji to przepuszczaj

		$role=$user->getRole();
		if(count($functionalities)==0)
			return true;

		foreach($functionalities as $functionality){
			foreach($role->getFunctionalities() as $userFunctionality){
				if($functionality==$userFunctionality->getCode())
					return true;
			}
		}

		return false;

	}

	private function getFunctionalities($extra){
		$functionalities=array();
		foreach($extra as $parameter){
			foreach($parameter as $name=>$value){
				if($name=='functionality')
					$functionalities[]=$value['name'];
			}

		}

		return $functionalities;
	}

}