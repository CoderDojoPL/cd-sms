<?php

spl_autoload_register(function($classname) {
	$path=__DIR__.'/engine/'.str_replace('\\','/' , $classname).'.php';
	if(is_file($path)){
		require $path;
	}
});