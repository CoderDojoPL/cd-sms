<?php

require_once __DIR__.'/../arbor/Root.php';

array_shift($argv);
$root=new Arbor\Root(true,true,'dev');
$root->executeCommand($argv);