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

require_once __DIR__.'/../arbor/core/Autoloader.php';

$autoloader=new Arbor\Core\Autoloader();

$env='prod';
$debug=false;
if(file_exists(__DIR__.'/../dev')){
	$env='dev';
	$debug=true;
}

$root=new Arbor\Root($autoloader,$debug,false,$env);
$root->executeRequest();
