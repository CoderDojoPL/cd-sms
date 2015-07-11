<?php

namespace Arbor\Core;

use Arbor\Core\ContenerServices;
use Arbor\Provider\Request;
use Arbor\Provider\Response;
use Arbor\Provider\Session;
use Arbor\Exception\ServiceNotFoundException;
use Arbor\Exception\MethodNotFoundException;
use Arbor\Core\ExecuteResources;

abstract class Command extends Container{
	private $executeResources;

    public function writeLn($data){
    	echo $data."\n\r";
    }

    public function write($data){
    	echo $data;
    }

}