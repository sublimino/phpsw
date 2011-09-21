<?php
use Doctrine\ORM\Tools\Setup;

require_once "Doctrine/ORM/Tools/Setup.php";
Setup::registerAutoloadPEAR();

require __DIR__ . '/lib/Fwk/Autoloader.php';
set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__ . '/lib');
Fwk_Autoloader::register();

$config = new Zend_Config_Ini(__DIR__ . '/config/config.ini');

// database configuration parameters
$conn = array(
	'driver' => 'pdo_mysql',
	'host' => 'localhost',
	'user' => $config->db->user,
	'password' => $config->db->password,
	'dbname' => $config->db->name
);

// Create a simple "default" Doctrine ORM configuration for XML Mapping
$isDevMode = true;
$config = Setup::createAnnotationMetadataConfiguration(array(__DIR__."/lib/App/Model"), $isDevMode);

// obtaining the entity manager
$entityManager = \Doctrine\ORM\EntityManager::create($conn, $config);

$helperSet = new Symfony\Component\Console\Helper\HelperSet(array(
	'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($entityManager)
));
