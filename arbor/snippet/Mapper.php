<?php

namespace Arbor\Snippet;
use Arbor\Core\Container;

class Mapper {
	
	public function cast(Container $container,$mapperName,$value){
		return $container->getService('mapper')->cast($container,$mapperName,$value);
	}
}
