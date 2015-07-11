<?php

namespace Arbor\Exception;
use Arbor\Core\Exception;

class RouteNotFoundException extends Exception{
	
	public function __construct($url){
		parent::__construct(3,'Route not found for url: '.$url.".","Page not found.");
	}
}