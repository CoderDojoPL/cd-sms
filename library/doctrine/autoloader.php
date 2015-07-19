<?php

/*
 * This file is part of the ArborPHP.
 * Copyright (c) NewClass (http://newclass.pl)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

spl_autoload_register(function($classname) {
	$path=__DIR__.'/engine/'.str_replace('\\','/' , $classname).'.php';
	if(is_file($path)){
		require $path;
	}
});