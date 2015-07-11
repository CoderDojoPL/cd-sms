<?php

spl_autoload_register(function($classname) {
	$path='../library/doctrine/engine/'.str_replace('\\','/' , $classname).'.php';
	if(is_file($path)){
		require $path;
	}
});