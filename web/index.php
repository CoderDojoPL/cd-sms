<?php

require_once __DIR__.'/../arbor/Root.php';
$env='prod';
$debug=false;
if(file_exists('../dev')){
	$env='dev';
	$debug=true;
}
$root=new Arbor\Root($debug,false,$env);
$root->executeRequest();
