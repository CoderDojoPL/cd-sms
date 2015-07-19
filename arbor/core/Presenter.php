<?php

/**
 * ArborPHP: Freamwork PHP (http://arborphp.com)
 * Copyright (c) NewClass (http://newclass.pl)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the file LICENSE
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) NewClass (http://newclass.pl)
 * @link          http://arborphp.com ArborPHP Project
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace Arbor\Core;

use Arbor\Provider\Response;
use Arbor\Contener\Config;
use Arbor\Contener\RequestConfig;

/**
 * Interface for generator view
 *
 * @author Michal Tomczak (michal.tomczak@arborphp.com)
 * @since 0.1.0
 */
interface Presenter{
	
	/**
	 * Method with render rules.
	 *
	 * @param \Arbor\Contener\RequestConfig $config
	 * @param \Arbor\Provider\Response $response
	 * @since 0.1.0
	 */
	public function render(RequestConfig $config , Response $response);

}