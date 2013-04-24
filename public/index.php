<?php
define('REQUEST_MICROTIME', microtime(true));

$applicationPath = dirname(__DIR__);
chdir($applicationPath);

if (file_exists("$applicationPath/vendor/autoload.php")) {
    $loader = include "$applicationPath/vendor/autoload.php";
}

if (!class_exists('Zend\Loader\AutoloaderFactory')) {
    throw new RuntimeException('Unable to load libraries.');
}

// Run the application!
Zend\Mvc\Application::init(require "$applicationPath/config/application.config.php")->run();
