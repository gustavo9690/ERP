<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/app/core/Autoload.php';

// registrar autoload
Autoload::register();

Cors::handle();

// iniciar aplicación
$app = new App();

$app->run();
