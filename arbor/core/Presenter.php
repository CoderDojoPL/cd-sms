<?php

namespace Arbor\Core;

use Arbor\Provider\Response;
use Arbor\Contener\Config;
use Arbor\Contener\RequestConfig;

interface Presenter{
	
	public function render(RequestConfig $config , Response $response);

}