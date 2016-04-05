<?php
error_reporting(E_ALL);
session_start();

// Define constant
define('BASE_ROOT', dirname(__DIR__));
define('VENDOR_ROOT', BASE_ROOT.'/vendor');
define('CONFIG_ROOT', BASE_ROOT.'/config');
define('RESOURCE_ROOT', BASE_ROOT.'/resource');
define('STORAGE_ROOT', BASE_ROOT.'/storage');

// Composer auto loader
require_once VENDOR_ROOT.'/autoload.php';

// Application configs
$config = [
    'app'  => require CONFIG_ROOT.'/app.php',
    'slim' => require CONFIG_ROOT.'/slim.php',
];

// Base configs
date_default_timezone_set($config['app']['timezone']);

// Import class
use Slim\App;

// Setup slim
$app = new App([
    'settings' => $config['slim']
]);

// Slim container
$container = $app->getContainer();

// Slim service providers
foreach($config['app']['providers'] as $provider) {
    $provider = new $provider($container);
    $provider->register();
}

$app->get('/', function ($request, $response, $args) {
    $this->logger->addInfo('called index handler');

    return $this->view->render($response, 'index.html');
})->setName('index');

$app->run();
