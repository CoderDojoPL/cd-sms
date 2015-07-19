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

use Arbor\Core\ContenerServices;
use Arbor\Provider\Request;
use Arbor\Provider\Response;
use Arbor\Provider\Session;
use Arbor\Exception\ServiceNotFoundException;
use Arbor\Exception\MethodNotFoundException;
use Arbor\Core\ExecuteResources;

/**
 * Main class for project commands. Configured in config/commands.xml
 *
 * @author Michal Tomczak (michal.tomczak@arborphp.com)
 * @since 0.1.0
 */
abstract class Command extends Container{

	/**
	 * Write data to console output and break line.
	 *
	 * @param string $data
	 * @since 0.1.0
	 */
    public function writeLn($data){
    	echo $data."\n\r";
    }

	/**
	 * Write data to console output
	 *
	 * @param string $data
	 * @since 0.1.0
	 */
    public function write($data){
    	echo $data;
    }

}