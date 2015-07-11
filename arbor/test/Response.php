<?php

namespace Arbor\Test;

class Response{
	private $response;
	private $content;
	public function __construct(\Arbor\Provider\Response $response,$content){
		$this->response=$response;
		$this->content=$content;
	}

	public function getContent(){
		return $this->content;
	}

}