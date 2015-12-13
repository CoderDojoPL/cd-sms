<?php
// bootstrap.php
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\DBAL\Event\Listeners\MysqlSessionInit;
require "../autoloader.php";

$paths = array("../../../entity");
$isDevMode = true;

// the connection configuration
$dbParams = array(
    'driver'   => 'pdo_mysql',
    'user'     => 'root',
    'password' => 'password',
    'dbname'   => 'dev',
    'charset' => 'utf8',
    'driverOptions' => array(1002=>'SET NAMES utf8')

);

$config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
$entityManager = EntityManager::create($dbParams, $config);
// $entityManager->getEventManager()->addEventSubscriber(new MysqlSessionInit("utf8", "utf8_unicode_ci"));

// Any way to access the EntityManager from  your application
$em = $entityManager;

$helperSet = new \Symfony\Component\Console\Helper\HelperSet(array(
    'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($em->getConnection()),
    'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em)
));


