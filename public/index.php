<?php

define('APPLICATION_PATH', realpath(__DIR__ . '/../'));

require_once 'Twig/Autoloader.php';
Twig_Autoloader::register();

require APPLICATION_PATH . '/lib/Fwk/Autoloader.php';
set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__ . '/../lib');
Fwk_Autoloader::register();

$config = new Zend_Config_Ini(__DIR__ . '/../config/config.ini');
// database configuration parameters
$conn = array(
	'driver' => 'pdo_mysql',
	'host' => 'localhost',
	'user' => $config->db->user,
	'password' => $config->db->password,
	'dbname' => $config->db->name
);

define('CONS_KEY', $config->twitter->key);
define('CONS_SECRET', $config->twitter->secret);

use Doctrine\ORM\Tools\Setup;
require_once "Doctrine/ORM/Tools/Setup.php";
Setup::registerAutoloadPEAR();

$isDevMode = true;
$config = Setup::createAnnotationMetadataConfiguration(array(__DIR__."/../lib/App/Model"), $isDevMode);

require_once APPLICATION_PATH . '/lib/sfServiceContainer/sfServiceContainerAutoloader.php';
sfServiceContainerAutoloader::register();

require 'SymfonyComponents/YAML/sfYaml.php';
$routes = sfYaml::load(file_get_contents(APPLICATION_PATH . '/config/routes.yaml'));

$sc = new sfServiceContainerBuilder;
$loader = new sfServiceContainerLoaderFileYaml($sc);
$loader->load(APPLICATION_PATH . '/config/container.yml');
$sc->setParameter('routes', $routes);
$sc->setParameter('templatedir', APPLICATION_PATH . '/templates');
$sc->setParameter('templatecache', APPLICATION_PATH . '/cache/templates');
$sc->setParameter('conn', $conn);
$sc->setParameter('config', $config);

$app = new Fwk_App($sc);
set_exception_handler(array($app, 'handleException'));
$request = new Fwk_Request($_SERVER, $_POST, $_GET);
$app->handle($request);
