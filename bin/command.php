<?php

require_once __DIR__.'/../arbor/Root.php';

array_shift($argv);
$env='prod';
$debug=false;
if(file_exists(__DIR__.'/../dev')){
	$env='dev';
	$debug=true;
}
$root=new Arbor\Root($debug,true,$env);
$root->executeCommand($argv);