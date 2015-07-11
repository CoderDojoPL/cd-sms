<?php

require '../arbor/Root.php';

array_shift($argv);
$root=new Arbor\Root(true,false,'dev');
$root->executeCommand($argv);